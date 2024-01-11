<?php
/**
 *  @author: $rachow
 *  @copyright: Coinhoppa
 * 
 *  Excel exporting service. Should be Queued.
 * 
 */

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ExcelExporter
{
    // @var - PhpSpreadsheet resource
    protected $spreadsheet = null;

    // @var - PhpSpreadsheet sheets
    protected $sheets = [];

    // @var - IO Writer Factory resource
    protected $writer = null;

    // @var - Array of Objects known as Collection
    // Illuminate\Support\Collection
    protected $collection = [];

    // @var - Array for export data
    protected $data = [];
 
	// @var - alphabets for excel columns
	protected $alphabets = [
		'A','B','C','D','E','F','G','H','I','J','K','L','M',
		'N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
    ];

    // @var - only export the specified columns.
    protected $exportColumns = [];

    // @var - export columns in uppsercase
    protected $exportColumnsUpper = false;

	/**
	 * Creates an instance 
	 * @param Illuminate\Support\Collection
	 * @return void
	*/
	public function __construct($data = null)
	{
		$this->build($data);
		$this->spreadsheet = new Spreadsheet();
		$this->sheets[] = $this->spreadsheet->getActiveSheet();

		// extending alphabets for large data sets
		$len = count($this->alphabets);
		for ($i=0; $i<$len; $i++) {
			$c = $this->alphabets[$i];
			for ($k=0; $k<$len; $k++) {
				$alpha = $c . $this->alphabets[$k];
				array_push($this->alphabets, $alpha);
			}
		}
	}

	/**
	 * Destroys the instance.
     * 
	 * @param none
	 * @return void
	*/
	public function __destruct()
	{
		// to avoid PHPSpreadsheets memory leaks
		// phpspreadsheet.readthedocs.io
		$this->spreadsheet->disconnectWorksheets();
		unset($this->spreadsheet);
	}

	/**
	 * Builds the data on the fly, 
	 * the native collect helper routine to create a Collection.
     * 
	 * @param  $data
	 * @return void
	*/
	private function build($data)
	{
		if (!is_null($data)) {
			if (is_array($data)) {
				$this->data = $data;
				$this->collection = collect($data);
			} elseif ($data instanceof Collection) {
				$this->collection = $data;
				$this->data = $data->toArray();
			}
		}
	}

    /**
     * Export the columns in uppercase.
     * 
     * @param  none
     * @return self
     */
    public function uppercase()
    {
        $this->exportColumnsUpper = true;
        return $this;
    }

    /**
     * Set the exporting columns for XLS.
     * 
     * @param  $columns
     * @return self
     */
    public function columns(array $columns)
    {
        $this->exportColumns = $columns;
        return $this;
    }

	/**
	 * Format the specific worksheet.
     * 
	 * @param  PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet
	 * @param  int $index - sheets index
	 * @param  array $options
	 * @return PhpOffice\PhpSpreadsheet\Worksheet\Worksheet 
	*/
	private function format(Worksheet $sheet, int $index = 0, array $options = [])
	{
		$ds = DIRECTORY_SEPARATOR;
		$logo_added = false;

		if ($index == 0) {
			if (array_key_exists('logo', $options)) {
				$public_path = public_path();
				$logo_path = $public_path . $ds . 'assets' . $ds . 'dist' . $ds . 'images' . $ds . 'logo.png';
				$logo_name = config('app.name');
				$logo_desc = $logo_name . ' logo';
				$logo_rows = 4;
				$logo_cols = 4;
				$logo_high = $logo_rows * 15;

                // override options passed here.
				foreach ($options['logo'] as $key => $val) {
					switch ($key) {
						case 'logo_path': $logo_path = $val; break;
						case 'logo_name': $logo_name = $val; break;
						case 'logo_desc': $logo_desc = $val; break;
						case 'logo_high': $logo_high = $val; break;
						case 'logo_rows': $logo_rows = $val; break;
						case 'logo_cols': $logo_cols = $val; break;
					}
				}

				if (file_exists($logo_path)) {
					$logo = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
					$logo->setName($logo_name);
					$logo->setDescription($logo_desc);
					$logo->setPath($logo_path);
					$logo->setHeight($logo_high);
					$logo->setWorksheet($sheet);
					$logo->setCoordinates('A1');

					$n = 1;
					for ($i=0; $i<$logo_cols; $i++) {
						$col = $this->alphabets[$logo_cols-1] . $n;
						$row = $this->alphabets[0] . $n;
						$sheet->mergeCells("$row:$col");
						$n++;
					}					
					$logo_added = true;
				}
			}
		}

		$format_arr = [
			'fill' 	=> [
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'startColor' => [ 'rgb' => '000000' ],
            ],
			'font'	=> [
				'bold'  => true,
				'color' => [ 'rgb' => 'FFFFFF' ],
            ],
        ];

		$highest_col = $sheet->getHighestColumn();
		$highest_col_indx =\PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highest_col);
		$incr = 1;

        // if there is logo to embed then we need to factor
		if ((bool) $logo_added) {
			$incr = $logo_rows + 1;
		}

		for ($row = $incr; $row <= $incr; $row++) {
			for ($col = 1; $col <= $highest_col_indx; $col++) {
				$sheet->getCellByColumnAndRow($col, $row)->getStyle()->applyFromArray($format_arr);
			}
		}

		foreach ($sheet->getColumnIterator() as $col) {
			$sheet->getColumnDimension($col->getColumnIndex())->setAutoSize(true);
		}
		$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
		$sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
		
        return $sheet;
	}

	/**
	 * Save the excel document to path or 
	 * you can adapt to save to Amazon S3 Storage and offer download link.
	 * 
	 * @param  $filename
	 * @param  $path - optional
	 * @param  $data - optional
	 * @return void
	*/
	public function save($filename, $path = null, $data = null)
	{
		$orig_filename = $filename;
		$filename = substr($filename, 0, (strlen ($filename)) - (strlen (strrchr($filename,'.'))));
		$this->build($data);

		// continue.
	}

	/**
	 * Download the excel document directly
	 * with the appropriate headers sent to browser.
	 *
	 * @param  $filename - download filename
	 * @param  $title    - sheet title
	 * @param  $data     - dataset
	 * @param  $logo     - logo present
	 * @return void
	*/
	public function download($filename, $title = null, $data = null, $logo = true)
	{
		$ds = DIRECTORY_SEPARATOR;
		$orig_filename = $filename;
		
		$this->build($data);
		$filename = preg_replace("/\.(xls|xslx)$/", '', $filename);
		$export_file = get_exports_directory() . $ds . $filename . '.xls';
		$sheet_indx = 0;
		$sheet = $this->sheets[$sheet_indx];

		$columns = [];
		$options = [];

		// grab the headers
		foreach ($this->data_array as $indx => $data_arr) {
			if (is_object($data_arr)) {
				foreach((array) $data_arr as $key => $val) {
					$columns[] = $key;
				}
			} else {
				foreach ($data_arr as $hdr => $val) {
					$columns[] = $hdr;
				}
			}
			break;
		}
		$header_write_i = 1;

		if ((bool) $logo) {
			$logo_rows = 4;	// number of rows to occupy for merge
			$logo_cols = 4;	// number of cols to occupy for merge
			$logo_high = $logo_rows * 15; // calculate the height

			$options['logo'] = [
				'logo_high' => $logo_high, // override default.
				'logo_rows' => $logo_rows,
				'logo_cols' => $logo_cols,
            ];
			$header_write_i = $logo_rows + 1;
		}

		$len = count($columns);
		$w_cnt = $header_write_i;
        $end_cnt = 0;

		for ($j=0; $j < $len; $j++) {
			// write headers to cells
			$cell_val = $columns[$j];
			$sheet->setCellValue($this->alphabets[$j].$w_cnt, "$cell_val");
			$end_cnt ++;
		}

		// formatting.
		$sheet = $this->format($sheet, $sheet_indx, $options);
		$sheet_title = "Export_" . date('dmY');

		if ($title !== null) {
			$sheet_title = $title;
		}

		$sheet->setTitle($sheet_title);
		$writer_cell = $header_write_i + 1;

		// writing to cells
		foreach ($this->data_array as $idx => $data_arr) {
			if (is_object($data_arr)) {
				$incr = 0;
				foreach ((array) $data_arr as $key => $val) {
					// horizontally loop cells
					$cell = $this->alphabets[$incr].$writer_cell;
					$value = ($val !== null) ? $val : "";
					$sheet->setCellValue($cell, $value);
					$incr ++;
				}
			} else {
				$incr = 0;
				foreach ($data_arr as $hdr => $val) {
					// loop all cells horizontal.
					$cell = $this->alphabets[$incr].':'.$writer_cell;
					$value = ($val !== null) ? $val : "";
					$sheet->setCellValue($cell, $value);
					$incr ++;
				}
			}
			$writer_cell ++;
		}

		$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($this->spreadsheet);
		$writer->save($export_file);

		// clean everything and close any open buffers.
		for ($k = 0; $k < ob_get_level(); $k++) {
			ob_end_clean();
		}

		header('Content-type: application/vnd.ms-excel');
		header('Content-disposition: attachment; filename=' . $filename . '.xls');
		header('Content-Length: ' . filesize($export_file));
		readfile($export_file);
        
        exit(0);
	}
}