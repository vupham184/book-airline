<?php

/**
 * CSV File Iterator
 *
 * @author Jon LaBelle
 */
class Ws_Csv_Collection implements SeekableIterator, Countable
{
	/**
	 * Must be greater than the longest line (in characters) to be found in
	 * the CSV file (allowing for trailing line-end characters).
	 *
	 * @var int
	 */
	const ROW_LENGTH = 0;

	/**
	 * Resource file pointer
	 */
	private $_file;
	private $_fileObj;

	/**
	 * CSV column delimeter
	 *
	 * @var string
	 */
	private $_delimiter;

	/**
	 * Create an instance of the CsvFileIterator class.
	 *
	 * Throws InvalidArgumentException if CSV file (string $file)
	 * does not exist.
	 *
	 * @param string $file The CSV file path.
	 * @param string $delimiter The default delimeter is a single comma (,)
	 */
	public function __construct($file, $delimiter = ',')
	{
		if (! file_exists($file))
		throw new InvalidArgumentException("{$file}");

		$this->_file = $file;
		$this->_fileObj = new SplFileObject($file, 'rt');
		$this->_delimiter = $delimiter;
	}

	/*
	 * @see Iterator::rewind()
	 */
	public function rewind()
	{
		$this->_fileObj->rewind();
	}

	/*
	 * @see Iterator::current()
	 */
	public function current()
	{
		return $this->_fileObj->fgetcsv($this->_delimiter);
	}

	/*
	 * @see Iterator::key()
	 */
	public function key()
	{
		return $this->_fileObj->key();
	}

	/*
	 * @see Iterator::next()
	 */
	public function next()
	{
		return $this->_fileObj->next();
	}

	/*
	 * @see Iterator::valid()
	 */
	public function valid()
	{
		return $this->_fileObj->valid();
	}

	/*
	 * @see Countable::count()
	 */
	public function count(){
		$file= $this->_file;
		$linecount = 0;
		$handle = fopen($file, "r");
		while(!feof($handle)){
			$line = fgets($handle, 4096);
			$linecount += substr_count($line, PHP_EOL);
		}
		fclose($handle);
		return $linecount;
	}

	/*
	 * @see SeekableIterator::seek()
	 */
	public function seek($position){
		return $this->_fileObj->seek($position);
	}
	
	public function fetchArrayMapHeader($limit = 30, $offset = 0, $options = array()) {
		$this->rewind();
		$header = $this->current();
		$this->seek($offset);
		$this->current();
		$this->next();
		$i = 0;
		$rows = array();
		while($i < $limit && !$this->_fileObj->eof()) {
			$i++;
			$row = $this->current();
			$buildRow = array();
			foreach($header as $key => $value) {
				$buildRow[$value] = $row[$key];
			}
			$rows[] = $buildRow;
			$this->next();
		}
		return $rows;
	}
}