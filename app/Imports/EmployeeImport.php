<?php

namespace App\Imports;

use App\Cabang;
use App\ClusterWorkingHour;
use App\Designation;
use App\EmployeeDetails;
use App\Http\Controllers\Admin\ManageEmployeesController;
use App\Role;
use App\SubCompany;
use App\User;
use App\Wilayah;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Modules\RestAPI\Entities\Department;

class EmployeeImport implements ToCollection, WithHeadingRow
{
    public $resp;

    public function collection(Collection $rows)
    {
        try {
            foreach ($rows as $row) {
                // check email user is unique
                $check_user = User::where('email', $row['email'])->count();
                if ($check_user > 0) {
                    throw new \Exception("Ada email yang telah digunakan/terdaftar");
                }
                $user = new User;
                $user->name = $row['nama'];
                $user->email = $row['email'];
                $user->password = Hash::make($row['password']);
                $user->gender = $row['gender'];
                $user->mobile = $row['mobile'];
                $canLogin = 'disable';
                if (isset($row['login'])) {
                    if ($row['login'] == '1') {
                        $canLogin = 'enable';
                    }
                }
                $user->login = $canLogin;
                $user->save();

                // create employee detail
                $employee = new EmployeeDetails;
                $employee->user_id = $user->id;
                $employee->employee_id = $row['id'];
                $employee->address = $row['alamat'];

                // get wilayah by name
                $getWilayah = Wilayah::where('name', $row['wilayah'])->first();
                if (!isset($getWilayah) && empty($getWilayah)) {
                    // throw new \Exception("Wilayah tidak ditemukan");
                    $getWilayah = new Wilayah;
                    $getWilayah->name = $row['wilayah'];
                    $getWilayah->save();
                }

                $employee->wilayah_id = $getWilayah->id;

                // get anak_perusahaan / sub company
                $getSubCompany = SubCompany::where('name', $row['anak_perusahaan'])->first();
                if (!isset($getSubCompany) && empty($getSubCompany)) {
                    // throw new \Exception("Anak perusahaan tidak ditemukan");
                    $getSubCompany = new SubCompany;
                    $getSubCompany->name = $row['anak_perusahaan'];
                    $getSubCompany->save();
                }

                $employee->sub_company_id = $getSubCompany->id;

                // get department 
                $getDepartment = Department::where('team_name', $row['departemen'])->first();
                if (!isset($getDepartment) && empty($getDepartment)) {
                    // throw new \Exception("Departemen tidak ditemukan");
                    $getDepartment = new Department;
                    $getDepartment->team_name = $row['departemen'];
                    $getDepartment->save();
                }
                $employee->department_id = $getDepartment->id;

                // get designation / jabatan
                $getDesignation = Designation::where('name', $row['jabatan'])->first();
                if (!isset($getDesignation) && empty($getDesignation)) {
                    // throw new \Exception("Jabatan tidak ditemukan");
                    $getDesignation = new Designation;
                    $getDesignation->name = $row['jabatan'];
                    $getDesignation->save();
                }

                $employee->designation_id = $getDesignation->id;

                // get cabang
                $getCabang = Cabang::where('name', $row['cabang'])->first();
                if (!isset($getCabang) && empty($getCabang)) {
                    // throw new \Exception("Jabatan tidak ditemukan");
                    $getCabang = new Cabang;
                    $getCabang->name = $row['cabang'];
                    $getCabang->save();
                }

                $employee->cabang_id = $getCabang->id;

                $employee->karyawan_khusus = $row['karyawan_khusus'];

                // get cluster by name
                $getCluster = ClusterWorkingHour::where('name', trim($row['klaster_jam_kerja']))->first();
                if (!isset($getCluster) && empty($getCluster)) {
                    throw new \Exception("Klaster tidak ditemukan");
                }
                $employee->cluster_working_hour_id = $getCluster->id;
                $employee->office_start_time = '-';
                $employee->office_end_time = '-';
                $employee->address = $row['alamat'];


                $manage_employee = new ManageEmployeesController;
                // set permission
                $dataAdditionalField = [
                    "karyawan_khusus" => $row['karyawan_khusus'],
                    "edit_lat_long" => $row['beri_akses_untuk_mengedit_latitude_dan_longitude'],
                    "report_task" => $row['menerima_atau_menolak_laporan_tugas'],
                    "report_task" => $row['menerima_atau_menolak_laporan_tugas'],

                    // tugas
                    "list_tugas" => $row['list_tugas'],
                    "create_tugas" => $row['create_tugas'],
                    "edit_tugas" => $row['edit_tugas'],
                    "delete_tugas" => $row['hapus_tugas'],

                    // proyek
                    "list_proyek" => $row['list_proyek'],
                    "create_proyek" => $row['create_proyek'],
                    "edit_proyek" => $row['edit_proyek'],
                    "delete_proyek" => $row['hapus_proyek'],

                    // pengumuman
                    "list_pengumuman" => $row['list_pengumuman'],
                    "create_pengumuman" => $row['create_pengumuman'],
                    "edit_pengumuman" => $row['edit_pengumuman'],
                    "delete_pengumuman" => $row['hapus_pengumuman'],

                    // masalah
                    "list_ticket" => $row['list_masalah'],
                    "create_ticket" => $row['create_masalah'],
                    "edit_ticket" => $row['edit_masalah'],
                    "delete_ticket" => $row['hapus_masalah'],
                    "reply_ticket" => $row['balas_masalah'],
                ];
                $json_additional_field = $manage_employee->saveAdditionalData((object) $dataAdditionalField);
                // save to db
                $employee->additional_field = $json_additional_field;

                // find user by name
                $atasan_satu = User::where('name', $row['atasan_1'])->first();
                if (isset($atasan_satu) && !empty($atasan_satu)) {
                    $atasan_satu = $atasan_satu->id;
                }

                $atasan_dua = User::where('name', $row['atasan_2'])->first();
                if (isset($atasan_dua) && !empty($atasan_dua)) {
                    $atasan_dua = $atasan_dua->id;
                }

                $atasan_tiga = User::where('name', $row['atasan_3'])->first();
                if (isset($atasan_tiga) && !empty($atasan_tiga)) {
                    $atasan_tiga = $atasan_tiga->id;
                }

                $dataPermissionRequire = [
                    "persetujuan_satu" => $atasan_satu,
                    "persetujuan_dua" => $atasan_dua,
                    "persetujuan_tiga" => $atasan_tiga
                ];
                $json_permission_require = $manage_employee->saveApprovalLevel((object) $dataPermissionRequire);
                // save to db
                $employee->permission_require = $json_permission_require;

                // get ijin anak perusahaan dipishkan dengan koma
                $getIjinAnakPerusahaan = $row['ijin_anak_perusahaan'];
                $arrIjinAnakPerusahaan = explode(",", $getIjinAnakPerusahaan);

                // lopp arr
                if (count($arrIjinAnakPerusahaan) > 0) {
                    foreach ($arrIjinAnakPerusahaan as &$val) {
                        // remove white space
                        $val  =  trim($val);
                        // check anak perusahaan exist
                        $getAnakPerusahaan = SubCompany::where('name', $val)->first();
                        if (isset($getAnakPerusahaan) && !empty($getAnakPerusahaan)) {
                            $val = strtolower($getAnakPerusahaan->name);
                            $val = str_replace(' ', '_', $val);
                            // append category
                            $val = "subcompany.$val";
                        } else {
                            $getAnakPerusahaan = new SubCompany;
                            $getAnakPerusahaan->name = $val;
                            $getAnakPerusahaan->save();

                            $val = strtolower($getAnakPerusahaan->name);
                            $val = str_replace(' ', '_', $val);
                            // append category
                            $val = "subcompany.$val";
                        }
                    }
                }
                // get ijin ijin_wilayah dipishkan dengan koma
                $getIjinWilayah = $row['ijin_wilayah'];
                $arrIjinWilayah = explode(",", $getIjinWilayah);

                // lopp arr
                if (count($arrIjinWilayah) > 0) {
                    foreach ($arrIjinWilayah as &$val) {
                        // remove white space
                        $val  =  trim($val);
                        // check anak perusahaan exist
                        $getWilayah = Wilayah::where('name', $val)->first();
                        if (isset($getWilayah) && !empty($getWilayah)) {
                            $val = strtolower($getWilayah->name);
                            $val = str_replace(' ', '_', $val);
                            // append category
                            $val = "wilayah.$val";
                        } else {
                            $getWilayah = new Wilayah;
                            $getWilayah->name = $val;
                            $getWilayah->save();

                            $val = strtolower($getWilayah->name);
                            $val = str_replace(' ', '_', $val);
                            // append category
                            $val = "wilayah.$val";
                        }
                    }
                }

                // get ijin cabang dipishkan dengan koma
                $getIjinCabang = $row['ijin_cabang'];
                $arrIjinCabang = explode(",", $getIjinCabang);
                // lopp arr
                if (count($arrIjinCabang) > 0) {
                    foreach ($arrIjinCabang as &$val) {
                        // remove white space
                        $val  =  trim($val);
                        // check anak perusahaan exist
                        $getCabang = Cabang::where('name', $val)->first();
                        if (isset($getCabang) && !empty($getCabang)) {
                            $val = strtolower($getCabang->name);
                            $val = str_replace(' ', '_', $val);
                            // append category
                            $val = "cabang.$val";
                        } else {
                            $getCabang = new Cabang;
                            $getCabang->name = $val;
                            $getCabang->save();

                            $val = strtolower($getCabang->name);
                            $val = str_replace(' ', '_', $val);
                            // append category
                            $val = "cabang.$val";
                        }
                    }
                }

                $dataPermission = [
                    "subcompany_rule" => $arrIjinAnakPerusahaan,
                    "wilayah_rule" => $arrIjinWilayah,
                    "cabang_rule" => $arrIjinCabang,
                ];
                $dataPermission = (object) $dataPermission;
                $json_permission = $manage_employee->savePermissionEmployee($dataPermission);
                // save to db
                $employee->permission = $json_permission;
                $employee->save();

                $role = Role::where('name', 'employee')->first();
                $user->attachRole($role->id);
            }
        } catch (\Throwable $e) {
            $this->resp = $e->getMessage();
            return $e->getMessage();
        }
    }
}
