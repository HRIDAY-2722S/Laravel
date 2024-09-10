<?php

namespace App\Http\Controllers;
use App\Models\Order;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\User;
use League\Csv\Writer;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PdfController extends Controller
{
    public function generate_user_pdf(){

        $user = User::where('role', 'user')->orderBy('id', 'ASC')->get();
        $data = [
            'users' => $user
        ];
    
        $pdfOptions = new Options();
        $pdfOptions->set('isHtml5ParserEnabled', true);
        $pdfOptions->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($pdfOptions);

        $html = view('pdf.sample', $data)->render();

        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'landscape');

        $dompdf->render();

        return $dompdf->stream('sample.pdf', [
            'Attachment' => false 
        ]);
    }


    public function generate_order_pdf(){
        
        $order = Order::orderBy('id', 'DESC')->get();
        $data = [
            'orders' => $order
        ];
        $pdfOptions = new Options();
        $pdfOptions->set('isHtml5ParserEnabled', true);
        $pdfOptions->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($pdfOptions);

        $html = view('pdf.order', $data)->render();

        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portraint');

        $dompdf->render();

        return $dompdf->stream('sample.pdf', [
            'Attachment' => false 
        ]);
    }


    public function import_csv(){
        return view('admin/import-csv');
    }

    public function insert_user(Request $request){
        try {
            $request->validate([
                'csvFile' => 'required',
            ],[
                'csvFile.required' => 'Kindly choose a CSV and an excel file to import user data.',
            ]);
    
            if ($request->hasFile('csvFile')) {
                $file = $request->file('csvFile');
                $filePath = $file->getRealPath();
                $fileExtension = $file->getClientOriginalExtension();

                if ($fileExtension == 'csv') {

                    $csvData = array_map('str_getcsv', file($filePath));
                    $headers = array_shift($csvData);
        
                    $existingUsers = [];
                    $newUsers = [];
        
                    foreach ($csvData as $row) {
                        if (count($row) < 3) {
                            throw new \Exception('Invalid CSV file format. Each row should have at least 3 columns.');
                        }
        
                        $email = $row[1];
                        if (User::where('email', $email)->exists()) {
                            $existingUsers[] = $email;
                        } else {
                            $user = new User();
                            $user->name = $row[0];
                            $user->email = $email;
                            $user->password = Hash::make($row[2]);
                            $user->remember_token = Str::random(40);
                            $newUsers[] = $user;
                        }
                    }
        
                    $existingCount = count($existingUsers);
                    $newCount = count($newUsers);

                    foreach ($newUsers as $user) {
                        if (!$user->save()) {
                            throw new \Exception('Failed to save user data to the database.');
                        }
                    }

                    if ($existingCount > 0) {
                        $errorMessage = "The following $existingCount emails already exist: ". implode('|| ', $existingUsers);
                        return redirect()->back()->with('error', $errorMessage);
                    }
        
                    $successMessage = "Successfully imported $newCount new users.";
                    return redirect()->back()->with('success', $successMessage);

                }elseif ($fileExtension == 'xls' || $fileExtension == 'xlsx') {
                    try {
                        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                        $spreadsheet = $reader->load($filePath);
                        $sheet = $spreadsheet->getActiveSheet();
                    
                        $existingUsers = [];
                        $newUsers = [];
                    
                        $rowIterator = $sheet->getRowIterator();
                        $rowNum = 1;
                        foreach ($rowIterator as $row) {
                            if ($rowNum > 1) {
                                $cellIterator = $row->getCellIterator();
                                $cells = [];
                                foreach ($cellIterator as $cell) {
                                    $cells[] = $cell->getValue();
                                }
                    
                                if (count($cells) < 3) {
                                    throw new \Exception('Invalid Excel file format. Each row should have at least 3 columns.');
                                }
                    
                                $email = $cells[1];
                                if (User::where('email', $email)->exists()) {
                                    $existingUsers[] = $email;
                                } else {
                                    $user = new User();
                                    $user->name = $cells[0];
                                    $user->email = $email;
                                    $user->password = Hash::make($cells[2]);
                                    $user->remember_token = Str::random(40);
                                    $newUsers[] = $user;
                                }
                            }
                            $rowNum++;
                        }
                    
                        $existingCount = count($existingUsers);
                        $newCount = count($newUsers);
                    
                        foreach ($newUsers as $user) {
                            if (!$user->save()) {
                                throw new \Exception('Failed to save user data to the database.');
                            }
                        }
                    
                        if ($existingCount > 0) {
                            $errorMessage = "The following $existingCount emails already exist: ". implode('|| ', $existingUsers);
                            return redirect()->back()->with('error', $errorMessage);
                        }
                    
                        $successMessage = "Successfully imported $newCount new users from Excel file.";
                        return redirect()->back()->with('success', $successMessage);
                    
                    } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
                        return redirect()->back()->with('error', 'The Excel file appears to be corrupted and cannot be read.');
                    }

                } else {
                    throw new \Exception('Invalid file type. Only CSV, XLS, and XLSX files are allowed.');
                }

            } else {
                throw new \Exception('No file selected!');
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function export_csv(){
        $users = User::where('role', 'user')->get();

        $csv = Writer::createFromFileObject(new \SplTempFileObject());
    
        $csv->insertOne(array('ID', 'Name', 'Email', 'Status', 'Email_verification', 'Role', 'Created_at', 'Updated_at'));
    
        foreach ($users as $user) {
            $status = $user->status == 0 ? 'Inactive' : 'Active';
            $email_verification = $user->email_verify == 1 ? 'Verified' : 'Not-Verified';
            $created_at = $user->created_at->format('d M, Y H:i:s A');
            $updated_at = $user->updated_at->format('d M, Y H:i:s');

            $csv->insertOne(array($user->name, $user->email, $status, $email_verification, $user->role, $created_at, $updated_at));
        }
    
        $headers = array(
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=usersdata.csv",
        );
    
        return response()->make($csv->getContent(), 200, $headers);
        
    }


    public function export_excel()
    {
        $users = User::where('role', 'user')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Name');
        $sheet->setCellValue('C1', 'Email');
        $sheet->setCellValue('D1', 'Status');
        $sheet->setCellValue('E1', 'Email_verification');
        $sheet->setCellValue('F1', 'Role');
        $sheet->setCellValue('G1', 'Created_at');
        $sheet->setCellValue('H1', 'Updated_at');


        $row = 2;
        foreach ($users as $user) {
            $status = $user->status == 0 ? 'Inactive' : 'Active';
            $email_verification = $user->email_verify == 1 ? 'Verified' : 'Not-Verified';
            
            $sheet->setCellValue('A' . $row, $user->id);
            $sheet->setCellValue('B' . $row, $user->name);
            $sheet->setCellValue('C' . $row, $user->email);
            $sheet->setCellValue('D' . $row, $status);
            $sheet->setCellValue('E' . $row, $email_verification);
            $sheet->setCellValue('F' . $row, $user->role);

            if ($user->created_at) {
                $sheet->setCellValue('G' . $row, $user->created_at->format('d M, y H:i:s A'));
            } else {
                $sheet->setCellValue('G' . $row, '');
            }
    
            if ($user->updated_at) {
                $sheet->setCellValue('H' . $row, $user->updated_at->format('d M, y H:i:s A'));
            } else {
                $sheet->setCellValue('H' . $row, '');
            }
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'usersdatain.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        $writer->save('php://output');
        exit();
    }
}

