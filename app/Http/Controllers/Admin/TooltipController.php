<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Datatable;
use App\Models\Tooltip;
use Toastr;
use DB;

class TooltipController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.tooltip.index');
    }

    public function wizard()
    {
        $wizard=DB::table('wizard')->first();

        return view('admin.wizard.index',compact('wizard'));
    }

    public function update_wizard(Request $request)
    {
        $id=$request->id;
        $change_title=$request->change_title;
        $change_details=$request->change_details;
        $sort_title=$request->sort_title;
        $sort_details=$request->sort_details;
        $tarief_title=$request->tarief_title;
        $tarief_details=$request->tarief_details;
        $wizard=$request->wizard;

        DB::table('wizard')->where('id',$id)->update([

            'change_title'=>$change_title,
            'change_details'=>$change_details,
            'sort_title'=>$sort_title,
            'sort_details'=>$sort_details,
            'tarief_title'=>$tarief_title,
            'tarief_details'=>$tarief_details,
            'wizard'=>$wizard



        ]);

        return back()->withErrors(['Successfully Updated']);
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tooltip = Tooltip::find($id);
        return view('admin.tooltip.edit', compact('tooltip'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Tooltip::where('id', $id)
                ->update([
                    'NL_tooltip' => $request->NL_tooltip,
                    'FR_tooltip' => $request->FR_tooltip,
                ]);
        Toastr::success('Tooltip content updated Successfully', 'Updated'); 
        return redirect()->route('tooltip.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    public function data(Request $request)
    {
        return (new Datatable(Tooltip::class, $request))
            ->select(array('id','slug','field_name','NL_tooltip','FR_tooltip'))   
            ->generalSearchOn(array('slug'))
            ->setSortFieldMap(array('id' => 'id'))
            ->getData();
    }
}
