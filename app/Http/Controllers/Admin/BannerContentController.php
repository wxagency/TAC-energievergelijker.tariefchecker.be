<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BannerContent;
use App\Http\Helpers\Datatable;
use Image;
use Toastr;

class BannerContentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.request-content.bannerdata.index');
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
        $banner = BannerContent::where('id', $id)
                        ->first();
        return view('admin.request-content.bannerdata.edit', compact('banner'));
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
        $this->validate($request, [
            'banner_image' => 'image|mimes:jpeg,png,jpg,gif,svg'
         ]);

         if ($request->banner_image != null) {
            $originalImage = $request->file('banner_image');
            $thumbnailImage = Image::make($originalImage);
            $originalPath = public_path() . '/Images/';
            $thumbnailImage->save($originalPath . time() . $originalImage->getClientOriginalName());

            $banner = BannerContent::find($id);
            $banner->banner_image = time() . $originalImage->getClientOriginalName();
            $banner->save();
        }
        BannerContent::where('id', $id)
                ->update([
                    'page_name' => $request->page,
                    'banner_content_in_english' => $request->content_eng,
                    'banner_content_in_NL' => $request->content_nl,
                    'banner_content_in_FR' => $request->content_fr,
                ]);
        Toastr::success('Banner Content updated Successfully', 'Updated');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        BannerContent::where('id', $id)->delete();
        return redirect()->route('banner-content.index');
    }
    
    public function data(Request $request)
    {
        return (new Datatable(BannerContent::class, $request))
            ->select(array('id','page_name','banner_content_in_english','banner_image'))
            ->generalSearchOn(array('page_name'))
            ->setSortFieldMap(array('id' => 'id'))
            ->getData();
    }
   
}
