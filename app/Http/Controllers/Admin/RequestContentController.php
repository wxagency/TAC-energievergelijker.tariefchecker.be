<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Title;
use App\Models\SubtitleContent;
use App\Models\Videocontent;
use DB;
use App\Http\Helpers\Datatable;
use Toastr;

class RequestContentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subcontent = SubtitleContent::select('subtitle_contents.id','subtitle_contents.title_id',
                                        'subtitle_contents.NL_subtitle','subtitle_contents.FR_subtitle',
                                        'subtitle_contents.NL_content','subtitle_contents.FR_content',
                                        'titles.NL_title','titles.FR_title')
                                        ->join('titles', 'titles.id', '=', 'subtitle_contents.title_id')
                                        ->get();
        return view('admin.request-content.requestdata.edit', compact('subcontent'));
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $videocontent = Videocontent::first();
        return view('admin.request-content.videocontent.edit', compact('videocontent'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
            $data = $request->all();
//            dd($data);
            $rules = [
                'video'          =>'mimes:mpeg,ogg,mp4,webm,3gp,mov,flv,avi,wmv,ts|max:100040'];
            $validator = Validator($data,$rules);

            if (isset($data['video']) && !empty($data['video'])) {
                if ($validator->fails()) {
                    return redirect()
                                    ->back()
                                    ->withErrors($validator)
                                    ->withInput();
                } else {
                    $video = $data['video'];
                    $input = time() . '.' . $video->getClientOriginalExtension();
                    $destinationPath = 'uploads/videos';
                    $video->move($destinationPath, $input);

                    $videocontent['video'] = $input;
                    $videocontent['title'] = $data['title'];
                    $videocontent['content'] = $data['description'];
                    $videocontent['created_at'] = date('Y-m-d h:i:s');
                    $videocontent['updated_at'] = date('Y-m-d h:i:s');
                    DB::table('videocontents')->where('id', $data['id'])->update($videocontent);
                    return redirect()->back()->with('upload_success', 'upload_success');
                }
            } else {
                Videocontent::where('id', $data['id'])
                        ->update([
                            'video' => $data['videolink'],
                            'NL_title' => $data['NL_title'],
                            'FR_title' => $data['FR_title'],
                            'NL_content' => $data['NL_description'],
                            'FR_content' => $data['FR_description']
                ]);
            }
            Toastr::success('Video Content updated Successfully', 'Updated');
            return back();
    }
    
//    public function addSubtitle()
//    {
//        return view('admin.request-content.requestdata.addsubtitle');
//    }
    
    public function dataStore(Request $request)
    {
        $title = Title::where('id', $request->titleid)
                ->update([
            'NL_title' => $request->NL_title,
            'FR_title' => $request->FR_title,        
        ]);
        Toastr::success('Title updated Successfully', 'Updated');
        return back();
    }
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $subtitle = SubtitleContent::find($id);
        return view('admin.request-content.requestdata.subedit', compact('subtitle'));
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
        SubtitleContent::where('id', $id)
                        ->update([
                            'NL_subtitle' => $request->NL_subtitle,
                            'FR_subtitle' => $request->FR_subtitle,
                            'NL_content' => $request->NL_description,
                            'FR_content' => $request->FR_description,
                        ]);
        return redirect()->route('request-content.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        SubtitleContent::where('id', $id)->delete();
        return redirect()->route('request-content.index');
    }
    
    public function data(Request $request)
    {
        return (new Datatable(SubtitleContent::class, $request))
            ->select(array('subtitle_contents.id','subtitle_contents.title_id','subtitle_contents.subtitle',
                                        'subtitle_contents.NL_subtitle','subtitle_contents.FR_subtitle',
                                        'subtitle_contents.NL_content','subtitle_contents.FR_content',
                                        'subtitle_contents.content','titles.NL_title','titles.FR_title'))
            ->join('titles', 'titles.id', '=', 'subtitle_contents.title_id')
            ->generalSearchOn(array('NL_subtitle'))
            ->setSortFieldMap(array('id' => 'subtitle_contents.id'))
            ->getData();
    }
}
