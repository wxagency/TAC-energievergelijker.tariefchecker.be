@extends('layouts.admin-layout')

@section('title', 'Home')



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
                            Edit Admin
                        </h3>
                    </div>
                    <div class="kt-portlet__head-tools">
                        <a href="{{route('admin.admin-users.index')}}" class="btn btn-secondary">
                            <span>
                                <i class="fa fa-chevron-left"></i>
                                <span>
                                    Back
                                </span>
                            </span>
                        </a>
                    </div>
                </div>

                <!--begin::Form-->

                <form class="kt-form kt-form--label-right" method="post" action="{{route('admin-users.update', $admin->id)}}"  enctype="multipart/form-data">
                    {{csrf_field()}}
                    <input type="hidden" name="_method" value ="PUT"/>
                    <div class="kt-portlet__body">  

                        <div class="form-group row {{ $errors->has('name') ? ' has-danger' : '' }}">
                            <label for="example-search-input" class="col-2 col-form-label">Name</label>
                            <div class="col-8">
                                <input class="form-control" type="text" id="example-search-input" placeholder="Name" name="name" value="{{ $admin->name }}" required="required">
                                @if ($errors->has('name'))
                                <div id="first_name-error" class="form-control-feedback">{{ $errors->first('name') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('email') ? ' has-danger' : '' }}">
                            <label for="example-search-input" class="col-2 col-form-label">Email</label>
                            <div class="col-8">
                                <input class="form-control" type="text" id="example-search-input" placeholder="Email" name="email" value="{{ $admin->email }}" required="required">
                                @if ($errors->has('email'))
                                <div id="first_name-error" class="form-control-feedback">{{ $errors->first('email') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('role') ? ' has-danger' : '' }}">
                            <label for="example-search-input" class="col-2 col-form-label">Role</label>
                            <div class="col-8">
                                <select class="form-control kt-input kt-input--solid" id="packageType" name="role" required="required">
                                    <option>--select--</option>
                                    @foreach($role as $value )
                                    <option value="{{$value->id}}" {{ $admin->role_id==$value->id ?'selected=""':'' }} >{{$value->guard_name}}</option>
                                    @endforeach
                                </select> 
                                @if ($errors->has('role'))
                                <span id="first_name-error" class="form-control-feedback">{{ $errors->first('role') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('role') ? ' has-danger' : '' }}">
                            <label for="example-search-input" class="col-2 col-form-label">Status</label>
                            <div class="col-8">
                                <select class="form-control kt-input kt-input--solid" id="packageType" name="status" required="required">
                                    <option>--select--</option>
                                    <option value="1" {{ $admin->status== 1 ?'selected=""':'' }} >Active</option>
                                    <option value="0" {{ $admin->status== 0 ?'selected=""':'' }} >Inactive</option>
                                   
                                </select> 
                                @if ($errors->has('status'))
                                <span id="first_name-error" class="form-control-feedback">{{ $errors->first('status') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group kt-form__group row">
                            <label for="example-text-input" class="col-2">
                                Enter New Password
                            </label>
                            <div class="col-8">
                                <input class="form-control kt-input" type="password"  name="password" placeholder="Enter Password">
                            </div>
                        </div>
                        <div class="form-group kt-form__group row">
                            <label for="example-text-input" class="col-2">
                                Re-enter Password
                            </label>
                            <div class="col-8">
                                <input class="form-control kt-input" type="password"  name="password_confirmation"  placeholder="Re-Enter Password">
                                @if ($errors->has('password'))
                                <span class="text-danger">*{{ $errors->first('password') }}</span>
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
                            <a href="{{ route('admin.admin-users.index') }}" class="btn btn-secondary">
                                Back
                            </a>
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




