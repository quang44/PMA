<?php

    namespace App\Http\Controllers;

    use App\Models\WarrantyCode;
    use App\Models\WarrantyCodeImport;
    use Illuminate\Http\Request;
    use Excel;

    class WarrantyCodeController extends Controller
    {

        function index(Request $request)
        {
            $status = null;
            $warranty_codes = WarrantyCode::orderBy('created_at', 'DESC');
            if ((isset($request->sort_status) ? $request->sort_status : -1) >= 0) {
                $status = $request->sort_status;

                $warranty_codes = $warranty_codes->where('status', $status);
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
                'code' => 'required|unique:warranty_codes,code,'.$id,
            ], [
                'code.required' => 'Vui lòng nhập mã bảo hành',
                'code.unique' => 'Mã bảo hành đã tồn tại'
            ]);

            $warrantyCode=WarrantyCode::findOrFail($id);
            $warrantyCode->update([
                'code' => $request->code
            ]);
            flash(translate('Warranty code has been update successfully'))->success();
            return redirect()->route('warranty_codes.index');
        }


        function importWarrantyCode()
        {

            return view('backend.warranty.warrantyCodes.upload');
        }


        public function warrantyCodeUpload(Request $request)
        {
            $request->validate(['bulk_file'=>'required|mimes:xlsx'],[
                'bulk_file.required'=>'không có phải nào được chọn',
                'bulk_file.mimes'=>'File không đúng định dạng xlsx'
            ]);
            if ($request->hasFile('bulk_file')) {
                $import = new WarrantyCodeImport;
                Excel::import($import, request()->file('bulk_file'));
            }

            return back();
        }

        function destroy($id)
        {
            WarrantyCode::findOrFail($id)->delete();
            flash(translate('Warranty code has been deleted successfully'))->success();
            return redirect()->route('warranty_codes.index');
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
