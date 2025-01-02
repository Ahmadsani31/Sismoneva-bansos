<?php
if (! function_exists('OptionAgama')) {
    function OptionAgama($selected = '')
    {
        $agama = \Illuminate\Support\Facades\DB::table('agama')->get();
        $opt = '<option value="">Pilih Salah Satu</option>';
        foreach ($agama as $va) {
            $sel = $va->id == $selected ? 'selected' : '';
            $opt .= '<option value="' . $va->id . '" ' . $sel . '>' . $va->nama . '</option>';
        }
        return $opt;
    }
}

if (! function_exists('OptionCreate')) {
    function OptionCreate($Key, $Name, $Selected)
    {

        $data = '';

        $Jumlah = count($Key);

        if ($Jumlah > 0) {

            for ($i = 0; $i < $Jumlah; $i++) {

                $selected = $Key[$i] == $Selected ? "selected" : "";

                $data .= '<option value ="' . $Key[$i] . '" ' . $selected . '>' . $Name[$i] . '</option>';
            }
        } else {

            $data .= '<option =""></option>';
        }

        return $data;
    }
}

if (! function_exists('OptionDaerahIndonesi')) {
    function OptionDaerahIndonesi($type, $primary, $selected = '')
    {
        $opt = '';
        switch ($type) {
            case 'provinsi':
                $provinsi = \Indonesia::allProvinces();
                foreach ($provinsi as $va) {
                    $sel = $va->id == $selected ? 'selected' : '';
                    $opt .= '<option value="' . $va->id . '" ' . $sel . '>' . $va->name . '</option>';
                }
                break;
            case 'kabupaten':
                if (!empty($primary)) {
                    $provinsi = \Indonesia::findProvince($primary, ['cities'])->cities->pluck('name', 'id');
                    foreach ($provinsi as $key => $name) {
                        $sel = $key == $selected ? 'selected' : '';
                        $opt .= '<option value="' . $key . '" ' . $sel . '>' . $name . '</option>';
                    }
                }
                break;
            case 'kecamatan':
                if (!empty($primary)) {
                    $provinsi = \Indonesia::findCity($primary, ['districts'])->districts->pluck('name', 'id');
                    foreach ($provinsi as $key => $name) {
                        $sel = $key == $selected ? 'selected' : '';
                        $opt .= '<option value="' . $key . '" ' . $sel . '>' . $name . '</option>';
                    }
                }

                break;
            case 'kelurahan':
                if (!empty($primary)) {
                    $provinsi = \Indonesia::findDistrict($primary, ['villages'])->villages->pluck('name', 'id');
                    foreach ($provinsi as $key => $name) {
                        $sel = $key == $selected ? 'selected' : '';
                        $opt .= '<option value="' . $key . '" ' . $sel . '>' . $name . '</option>';
                    }
                }

                break;
            default:
                $opt .= '<option value="">Empty</option>';
                break;
        }

        return $opt;
    }
    if (! function_exists('Option')) {
        function Option($Table, $Primary, $Selected, $Nama, $where = '')
        {

            $data = "";

            switch ($Table) {
                case 'program':
                    $sql = \Illuminate\Support\Facades\DB::table('program');
                    $query =  $sql->get();
                    break;
                case 'satuan_bahan':
                    $sql = \Illuminate\Support\Facades\DB::table('satuan_bahan');
                    $query =  $sql->get();
                    break;
                case 'posisi':
                    $sql = \Illuminate\Support\Facades\DB::table('posisi');
                    if ($where != '') {
                        $sql->where($where);
                    }
                    $query =  $sql->get();
                    break;
            }

            foreach ($query as $fetch) {

                if ($Selected == $fetch->$Primary) {
                    $sel = "selected";
                } else {
                    $sel = "";
                }

                if ($Nama != "") {
                    $Nama = $Nama;
                } else {
                    $Nama = "nama";
                }

                $data .= '<option value="' . $fetch->$Primary . '" ' . $sel . '>' . $fetch->$Nama . '</option>';
            }

            return $data;
        }
    }
}

if (! function_exists('DaerahID')) {
    function DaerahID()
    {
        return [
            'provinsi' => 3,
            'kabupaten' => 72
        ];
    }
}

if (! function_exists('monthOptions')) {
    function monthOptions($selected)
    {
        $months = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        ];

        $options = '';
        foreach ($months as $key => $month) {
            $isSelected = $selected == $key ? 'selected' : '';
            $options .= "<option value=" . $key . " $isSelected>$month</option>";
        }

        return $options;
    }
}
