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
                          Add Request Content
                        </h3>
                      </div>
                      <div class="kt-portlet__head-tools">
                    
                     </div>
                    </div>
                    <!--begin::Form-->
                  
                      <form class="kt-form kt-form--label-right" method="POST" action="{{route('request-content.add')}}" enctype="multipart/form-data">
                        {{csrf_field()}}
                      <div class="kt-portlet__body">  
                          <input type="hidden" value="{{$subcontent[0]['title_id']}}" name="titleid">
                          <div class="form-group row {{ $errors->has('NL_title') ? ' has-danger' : '' }}">
                              <label for="example-search-input" class="col-3 col-form-label" rel="tooltip" title="Title in dutch">NL Title</label>
                              <div class="col-7">
                                  <input class="form-control" type="text" name="NL_title" id="title" value="{{$subcontent[0]['NL_title']}}">
                                    @if ($errors->has('NL_title'))
                                  <div id="first_name-error" class="form-control-feedback text-danger">{{ $errors->first('NL_title') }}</div>
                                  @endif
                              </div>
                            </div>  
                              
                            <div class="form-group row {{ $errors->has('FR_title') ? ' has-danger' : '' }}">
                              <label for="example-search-input" class="col-3 col-form-label"rel="tooltip" title="Title in french">FR Title</label>
                              <div class="col-7">
                                  <input class="form-control" type="text" name="FR_title" id="FR_title" value="{{$subcontent[0]['FR_title']}}">
                                    @if ($errors->has('FR_title'))
                                  <div id="first_name-error" class="form-control-feedback text-danger">{{ $errors->first('FR_title') }}</div>
                                  @endif
                              </div>
                            </div>  
                          
                          <div class="offset-5 kt-portlet__foot kt-portlet__foot--fit ">
                            <div class="kt-form__actions">
                                <button type="submit" class="btn btn-success submit" rel="tooltip" title="Save the changes">
                                    Save Title
                                </button>
                              </div>
                        </div>   
<!--                          @foreach ($subcontent as $content)
                          <div class="form-group row {{ $errors->has('subtitle') ? ' has-danger' : '' }}">
                              <label for="example-search-input" class="col-3 col-form-label">SubTitle</label>
                              <div class="col-7">
                                  <input class="form-control" type="text" name="subtitle" id="subtitle" value="{{$content->subtitle}}">
                                    @if ($errors->has('subtitle'))
                                  <div id="first_name-error" class="form-control-feedback text-danger">{{ $errors->first('subtitle') }}</div>
                                  @endif
                              </div>
                          </div>
                          <div class="form-group row {{ $errors->has('area') ? ' has-danger' : '' }}">
                              <label for="example-search-input" class="col-3 col-form-label">Description</label>
                              <div class="col-7">
                               <textarea class="form-control m-input m-input--solid"  id="summernote" aria-describedby="Description" placeholder="Description" name="description" >{{$content->content}}</textarea>
                                @if ($errors->has('description'))

                                <span class="text-danger"> *{{ $errors->first('description') }}</span>
                                @endif
                              </div>
                          </div>
                          @endforeach-->
                          
                      </div>

<!--                        <div class="clearfix"></div>
                        <div class="offset-5 kt-portlet__foot kt-portlet__foot--fit ">
                            <div class="kt-form__actions">
                                <button type="submit" class="btn btn-success submit">
                                    Save Changes
                                </button>
                            </div>
                        </div>-->
                    </form>
                    <!--begin: Datatable--> 

                          <div class="kt-datatable" id="kt_datatable">

                          </div>
                          <!--end: Datatable-->
                  </div>

                  <!--end::Portlet-->
                </div>


              
              </div>
            </div>

            <!-- end:: Content -->

  @endsection
  @push('scripts')
  <script type="text/javascript">
  
  // kt-datatable

$(document).ready(function(){

  var datatable = $('#kt_datatable').KTDatatable({
        
        // datasource definition
        data: {
          type: 'remote',
          source: {
            read: {
              url: "{{ route('request-content.data') }}",
              map: function(raw) {
                // sample data mapping
                var dataSet = raw;
                if (typeof raw.data !== 'undefined') {
                  dataSet = raw.data;
                }
                return dataSet;
              },
            },
          },
          pageSize: 10,
          serverPaging: true,
          serverFiltering: true,
          serverSorting: true,
        },
        
        // layout definition
        layout: {
          scroll: true,
          footer: false,
        },

        // column sorting
        sortable: true,

        pagination: true,

        search: {
          input: $('#generalSearch'),
        },
        
        // columns definition
        columns: [
          
          {
            field: 'NL_subtitle',
            title: 'Subtitle',
          }, 
            {
            field: 'NL_content',
            width: 300,
            title: 'Description',
          }, 
       
        {
          field:"Actions",
          width:110,
          title:"Actions",
          sortable:!1,
          overflow:"visible",
          template:function(t,e,a) {
              let actionEdit = "{{route('request-content.index')}}/" + t.id + '/edit';
              let actionDelete = "{{route('request-content.index')}}/" + t.id;
              
            return `
                <a href="${actionEdit}"  class="kt-portlet__nav-link btn kt-btn kt-btn--hover-accent kt-btn--icon kt-btn--icon-only kt-btn--pill" title="Edit details" ><i class="fas fa-user-edit"></i></a>
                <a href="${actionDelete}" data-method="delete" data-confirm="Are you sure you want to delete ?" class="kt-portlet__nav-link btn kt-btn kt-btn--hover-danger kt-btn--icon kt-btn--icon-only kt-btn--pill" title="Delete"><i class="fas fa-trash"></i></a>`
          }
        
        }
         
          ]

    }).on('kt-datatable--on-layout-updated', function () {
       laravelActions.initialize();
     });
          

});

</script>

@endpush