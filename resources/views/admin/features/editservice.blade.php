@extends('layouts.admin-layout')

@section('title', 'Request page data')



@section('content')
  <!-- begin:: Content -->
            <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
              <div class="row">
                  
                <div class="col-md-12">
                    
                  <!--begin::Portlet-->
                  <div class="kt-portlet">
                    <div class="kt-portlet__head">
                      <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                          Edit Service Limitations
                        </h3>
                      </div>
                      <div class="kt-portlet__head-tools">
                    
                     </div>
                    </div>

                    <!--begin::Form-->
                  
                      <form class="kt-form kt-form--label-right" method="POST" action="{{route('feature.store')}}" enctype="multipart/form-data" files="true">
                        {{csrf_field()}}
                        
                      <div class="kt-portlet__body"> 
                          <input type="hidden" name="id" value="{{$service->id}}">
                          <div class="form-group row {{ $errors->has('NL_description') ? ' has-danger' : '' }}">
                              <label for="example-search-input" class="col-3 col-form-label">NL Description</label>
                              <div class="col-8">
                                <textarea class="form-control m-input m-input--solid"  id="summernote" required="required" aria-describedby="Description" placeholder="Content" name="NL_description" >{{$service->NL_description}}</textarea>
                                @if ($errors->has('NL_description'))

                                <span class="text-danger"> *{{ $errors->first('NL_description') }}</span>
                                @endif
                              </div>
                          </div>
                          <div class="form-group row {{ $errors->has('FR_description') ? ' has-danger' : '' }}">
                              <label for="example-search-input" class="col-3 col-form-label">FR Description</label>
                              <div class="col-8">
                                  <textarea class="form-control m-input m-input--solid" required="required"  id="summernote" aria-describedby="Description" placeholder="Content" name="FR_description" rows="6">{{$service->FR_description}}</textarea>
                                @if ($errors->has('FR_description'))

                                <span class="text-danger"> *{{ $errors->first('FR_description') }}</span>
                                @endif
                              </div>
                          </div>
                      </div>

                        <div class="clearfix"></div>
                        <div class="offset-5 kt-portlet__foot kt-portlet__foot--fit ">
                            <div class="kt-form__actions">
                                <button type="submit" class="btn btn-success submit">
                                    Save Changes
                                </button>
<!--                                <a href="{{ route('language.index') }}" class="btn btn-secondary">
                                Back
                                </a>-->
                                <input type="hidden" value="{{ csrf_token() }}" name="_token">
                            </div>
                        </div>
                    </form>
                  </div>

                  <!--end::Portlet-->
                </div>


              
              </div>
            </div>

            <!-- end:: Content -->

  @endsection

