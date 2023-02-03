<?php

    namespace App\Http\Controllers;

    use App\Models\WarrantyCode;
    use App\Models\WarrantyCodeImport;
    use Excel;
    use Illuminate\Http\Request;

    class WarrantyCodeController extends Controller
    {

        function index(Request $request)
        {
            $status = null;
            $warranty_codes = WarrantyCode::query()->orderByDesc('updated_at');
            if ((isset($request->sort_status) ? $request->sort_status : -1) >= 0) {
                $status = $request->sort_status;
                $warranty_codes = $warranty_codes->where('status', $status);
            }

            if (!empty($request->search)) {
                $warranty_codes = $warranty_codes->where('code', $request->search);
            }

            $warranty_codes = $warranty_codes->paginate(15);

            return view('backend.warranty.warrantyCodes.index', compact('warranty_codes', 'status'));
        }

        function create()
        {
            return view('backend.warranty.warrantyCodes.create');

        }


        function store(Request $request)
        {
            $request->validate([
                'code' => 'required|unique:warranty_codes',
            ], [
                'code.required' => 'Vui lòng nhập mã bảo hành',
                'code.unique' => 'Mã bảo hành đã tồn tại'
            ]);
            WarrantyCode::create([
                'code' => $request->code
            ]);
            flash(translate('Warranty code has been add new successfully'))->success();
            return redirect()->route('warranty_codes.index');
        }

        function edit($id)
        {
            $warranty_code=WarrantyCode::find(decrypt($id));
            return view('backend.warranty.warrantyCodes.edit',compact('warranty_code'));
        }

        function update(Request $request,$id)
        {
            $request->validate([
                'code' => 'required|unique:warranty_codes,code,' . $id,
            ], [
                'code.required' => 'Vui lòng nhập mã bảo hành',
                'code.unique' => 'Mã bảo hành đã tồn tại'
            ]);

            $warrantyCode = WarrantyCode::findOrFail($id);
            $warrantyCode->update([
                'code' => $request->code
            ]);
            flash(translate('Warranty code has been update successfully'))->success();
            return redirect()->route('warranty_codes.index');
        }

        function ChangeStatus(Request $request)
        {
            $warrantyCode = WarrantyCode::query()->findOrFail($request->id);
            if ($warrantyCode) {
                $warrantyCode->update(['status' =>0, 'use_at' => null]);
                return response([
                    'result' => true,
                ]);
            } else {
                return response([
                    'result' => false,
                ]);
            }

    }


        function importWarrantyCode()
        {

            return view('backend.warranty.warrantyCodes.upload');
        }


        public function warrantyCodeUpload(Request $request)
        {
            $request->validate(['bulk_file' => 'required|mimes:xlsx'], [
                'bulk_file.required' => 'không có file nào được chọn',
                'bulk_file.mimes' => 'File không đúng định dạng xlsx'
            ]);
            if ($request->hasFile('bulk_file')) {
                $import = new WarrantyCodeImport;
                Excel::import($import, request()->file('bulk_file'));
            }
            flash(translate('Warranty Code imported successfully'))->success();
            return back();
        }

        function destroy($id)
        {
            WarrantyCode::findOrFail($id)->delete();
            flash(translate('Warranty code has been deleted successfully'))->success();
            return back();
        }


        public function bulk_warranty_code_delete(Request $request)
        {
            if ($request->id) {
                foreach ($request->id as $warranty_id) {
                    WarrantyCode::findOrFail($warranty_id)->delete();
                }
            }

            return 1;
        }



    }
