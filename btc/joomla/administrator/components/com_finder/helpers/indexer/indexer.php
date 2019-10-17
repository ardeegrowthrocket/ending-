<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_finder
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::register('FinderIndexerHelper', __DIR__ . '/helper.php');
JLoader::register('FinderIndexerParser', __DIR__ . '/parser.php');
JLoader::register('FinderIndexerStemmer', __DIR__ . '/stemmer.php');
JLoader::register('FinderIndexerTaxonomy', __DIR__ . '/taxonomy.php');
JLoader::register('FinderIndexerToken', __DIR__ . '/token.php');

jimport('joomla.filesystem.file');

/**
 * Main indexer class for the Finder indexer package.
 *
 * The indexer class provides the core functionality of the Finder
 * search engine. It is responsible for adding and updating the
 * content links table; extracting and scoring tokens; and maintaining
 * all referential information for the content.
 *
 * Note: All exceptions thrown from within this class should be caught
 * by the controller.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_finder
 * @since       2.5
 */
abstract class FinderIndexer
{
	/**
	 * The title context identifier.
	 *
	 * @var    integer
	 * @since  2.5
	 */
	const TITLE_CONTEXT = 1;

	/**
	 * The text context identifier.
	 *
	 * @var    integer
	 * @since  2.5
	 */
	const TEXT_CONTEXT = 2;

	/**
	 * The meta context identifier.
	 *
	 * @var    integer
	 * @since  2.5
	 */
	const META_CONTEXT = 3;

	/**
	 * The path context identifier.
	 *
	 * @var    integer
	 * @since  2.5
	 */
	const PATH_CONTEXT = 4;

	/**
	 * The misc context identifier.
	 *
	 * @var    integer
	 * @since  2.5
	 */
	const MISC_CONTEXT = 5;

	/**
	 * The indexer state object.
	 *
	 * @var    object
	 * @since  2.5
	 */
	public static $state;

	/**
	 * The indexer profiler object.
	 *
	 * @var    object
	 * @since  2.5
	 */
	public static $profiler;

	/**
	 * Returns a reference to the FinderIndexer object.
	 *
	 * @return  FinderIndexer instance based on the database driver
	 *
	 * @since   3.0
	 * @throws  RuntimeException if driver class for indexer not present.
	 */
	public static function getInstance()
	{
		// Setup the adapter for the indexer.
		$format = JFactory::getDbo()->name;

		if ($format == 'mysqli')
		{
			$format = 'mysql';
		}
		elseif ($format == 'sqlazure')
		{
			$format = 'sqlsrv';
		}
		$path = __DIR__ . '/driver/' . $format . '.php';
		$class = 'FinderIndexerDriver' . ucfirst($format);

		// Check if a parser exists for the format.
		if (file_exists($path))
		{
			// Instantiate the parser.
			include_once $path;
			return new $class;
		}
		else
		{
			// Throw invalid format exception.
			throw new RuntimeException(JText::sprintf('COM_FINDER_INDEXER_INVALID_DRIVER', $format));
		}
	}

	/**
	 * Method to get the indexer state.
	 *
	 * @return  object  The indexer state object.
	 *
	 * @since   2.5
	 */
	public static function getState()
	{
		// First, try to load from the internal state.
		if (!empty(self::$state))
		{
			return self::$state;
		}

		// If we couldn't load from the internal state, try the session.
		$session = JFactory::getSession();
		$data = $session->get('_finder.state', null);

		// If the state is empty, load the values for the first time.
		if (empty($data))
		{
			$data = new JObject;

			// Load the default configuration options.
			$data->options = JComponentHelper::getParams('com_finder');

			// Setup the weight lookup information.
			$data->weights = array(
				self::TITLE_CONTEXT	=> round($data->options->get('title_multiplier', 1.7), 2),
				self::TEXT_CONTEXT	=> round($data->options->get('text_multiplier', 0.7), 2),
				self::META_CONTEXT	=> round($data->options->get('meta_multiplier', 1.2), 2),
				self::PATH_CONTEXT	=> round($data->options->get('path_multiplier', 2.0), 2),
				self::MISC_CONTEXT	=> round($data->options->get('misc_multiplier', 0.3), 2)
			);

			// Set the current time as the start time.
			$data->startTime = JFactory::getDate()->toSQL();

			// Set the remaining default values.
			$data->batchSize = (int) $data->options->get('batch_size', 50);
			$data->batchOffset = 0;
			$data->totalItems = 0;
			$data->pluginState = array();
		}

		// Setup the profiler if debugging is enabled.
		if (JFactory::getApplication()->getCfg('debug'))
		{
			self::$profiler = JProfiler::getInstance('FinderIndexer');
		}

		// Setup the stemmer.
		if ($data->options->get('stem', 1) && $data->options->get('stemmer', 'porter_en'))
		{
			FinderIndexerHelper::$stemmer = FinderIndexerStemmer::getInstance($data->options->get('stemmer', 'porter_en'));
		}

		// Set the state.
		self::$state = $data;

		return self::$state;
	}

	/**
	 * Method to set the indexer state.
	 *
	 * @param   object  $data  A new indexer state object.
	 *
	 * @return  boolean  True on success, false on failure.
	 *
	 * @since   2.5
	 */
	public static function setState($data)
	{
		// Check the state object.
		if (empty($data) || !$data instanceof JObject)
		{
			return false;
		}

		// Set the new internal state.
		self::$state = $data;

		// Set the new session state.
		$session = JFactory::getSession();
		$session->set('_finder.state', $data);

		return true;
	}

	/**
	 * Method to reset the indexer state.
	 *
	 * @return  void
	 *
	 * @since   2.5
	 */
	public static function resetState()
	{
		// Reset the internal state to null.
		self::$state = null;

		// Reset the session state to null.
		$session = JFactory::getSession();
		$session->set('_finder.state', null);
	}

	/**
	 * Method to index a content item.
	 *
	 * @param   FinderIndexerResult  $item    The content item to index.
	 * @param   string               $format  The format of the content. [optional]
	 *
	 * @return  integer  The ID of the record in the links table.
	 *
	 * @since   2.5
	 * @throws  Exception on database error.
	 */
	abstract public function index($item, $format = 'html');

	/**
	 * Method to remove a link from the index.
	 *
	 * @param   integer  $linkId  The id of the link.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   2.5
	 * @throws  Exception on database error.
	 */
	abstract public function remove($linkId);

	/**
	 * Method to optimize the index. We use this method to remove unused terms
	 * and any other optimizations that might be necessary.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   2.5
	 * @throws  Exception on database error.
	 */
	abstract public function optimize();

	/**
	 * Method to get a content item's signature.
	 *
	 * @param   object  $item  The content item to index.
	 *
	 * @return  string  The content item's signature.
	 *
	 * @since   2.5
	 */
	protected static function getSignature($item)
	{
		// Get the indexer state.
		$state = self::getState();

		// Get the relevant configuration variables.
		$config = array();
		$config[] = $state->weights;
		$config[] = $state->options->get('stem', 1);
		$config[] = $state->options->get('stemmer', 'porter_en');

		return md5(serialize(array($item, $config)));
	}

	/**
	 * Method to parse input, tokenize it, and then add it to the database.
	 *
	 * @param   mixed    $input    String or resource to use as input. A resource
	 *                             input will automatically be chunked to conserve
	 *                             memory. Strings will be chunked if longer than
	 *                             2K in size.
	 * @param   integer  $context  The context of the input. See context constants.
	 * @param   string   $lang     The language of the input.
	 * @param   string   $format   The format of the input.
	 *
	 * @return  integer  The number of tokens extracted from the input.
	 *
	 * @since   2.5
	 */
	protected function tokenizeToDB($input, $context, $lang, $format)
	{
		$count = 0;
		$buffer = null;

		// If the input is a resource, batch the process out.
		if (is_resource($input))
		{
			// Batch the process out to avoid memory limits.
			while (!feof($input))
			{
				// Read into the buffer.
				$buffer .= fread($input, 2048);

				// If we haven't reached the end of the file, seek to the last
				// space character and drop whatever is after that to make sure
				// we didn't truncate a term while reading the input.
				if (!feof($input))
				{
					// Find the last space character.
					$ls = strrpos($buffer, ' ');

					// Adjust string based on the last space character.
					if ($ls)
					{
						// Truncate the string to the last space character.
						$string = substr($buffer, 0, $ls);

						// Adjust the buffer based on the last space for the
						// next iteration and trim.
						$buffer = JString::trim(substr($buffer, $ls));
					}
					// No space character was found.
					else
					{
						$string = $buffer;
					}
				}
				// We've reached the end of the file, so parse whatever remains.
				else
				{
					$string = $buffer;
				}

				// Parse the input.
				$string = FinderIndexerHelper::parse($string, $format);

				// Check the input.
				if (empty($string))
				{
					continue;
				}

				// Tokenize the input.
				$tokens = FinderIndexerHelper::tokenize($string, $lang);

				// Add the tokens to the database.
				$count += $this->addTokensToDB($tokens, $context);

				// Check if we're approaching the memory limit of the token table.
				if ($count > self::$state->options->get('memory_table_limit', 30000))
				{
					self::toggleTables(false);
				}

				unset($string);
				unset($tokens);
			}
		}
		// If the input is greater than 2K in size, it is more efficient to
		// batch out the operation into smaller chunks of work.
		elseif (strlen($input) > 2048)
		{
			$start = 0;
			$end = strlen($input);
			$chunk = 2048;

			// As it turns out, the complex regular expressions we use for
			// sanitizing input are not very efficient when given large
			// strings. It is much faster to process lots of short strings.
			while ($start < $end)
			{
				// Setup the string.
				$string = substr($input, $start, $chunk);

				// Find the last space character if we aren't at the end.
				$ls = (($start + $chunk) < $end ? strrpos($string, ' ') : false);

				// Truncate to the last space character.
				if ($ls !== false)
				{
					$string = substr($string, 0, $ls);
				}

				// Adjust the start position for the next iteration.
				$start += ($ls !== false ? ($ls + 1 - $chunk) + $chunk : $chunk);

				// Parse the input.
				$string = FinderIndexerHelper::parse($string, $format);

				// Check the input.
				if (empty($string))
				{
					continue;
				}

				// Tokenize the input.
				$tokens = FinderIndexerHelper::tokenize($string, $lang);

				// Add the tokens to the database.
				$count += $this->addTokensToDB($tokens, $context);

				// Check if we're approaching the memory limit of the token table.
				if ($count > self::$state->options->get('memory_table_limit', 30000))
				{
					self::toggleTables(false);
				}
			}
		}
		else
		{
			// Parse the input.
			$input = FinderIndexerHelper::parse($input, $format);

			// Check the input.
			if (empty($input))
			{
				return $count;
			}

			// Tokenize the input.
			$tokens = FinderIndexerHelper::tokenize($input, $lang);

			// Add the tokens to the database.
			$count = $this->addTokensToDB($tokens, $context);
		}

		return $count;
	}

	/**
	 * Method to add a set of tokens to the database.
	 *
	 * @param   mixed  $tokens   An array or single FinderIndexerToken object.
	 * @param   mixed  $context  The context of the tokens. See context constants. [optional]
	 *
	 * @return  integer  The number of tokens inserted into the database.
	 *
	 * @since   2.5
	 * @throws  Exception on database error.
	 */
	abstract protected function addTokensToDB($tokens, $context = '');

	/**
	 * Method to switch the token tables from Memory tables to MyISAM tables
	 * when they are close to running out of memory.
	 *
	 * @param   boolean  $memory  Flag to control how they should be toggled.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   2.5
	 * @throws  Exception on database error.
	 */
	abstract protected function toggleTables($memory);
}
