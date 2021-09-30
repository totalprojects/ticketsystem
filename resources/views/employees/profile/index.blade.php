@extends('adminlte::page')

@section('title', 'Employee Profile')

@section('content_header')
    <!-- <h1>employees List</h1> -->
@stop

@section('content')
    <style>
        .no-assets{
            height: 30vh;
            background-color: #cccc;
            display: flex;
            align-items: center;
            flex: 1;
            justify-content: center;
        }
    </style>
    <div class="tab-content p-1">
        <div class="tab-pane active dx-viewport" id="employees">
           
            <div class="demo-container">
                <div class="top-info">
                  <div class="table-heading-custom"><h4 class="right"><i class="fas fa-user"></i> Employee Profile </h4></div>
    
                </div>
                <div id="employee-list-div" style="height:auto"></div>
            </div>
        </div>
    </div>
  
  <!-- Assets Modal -->
  <div class="modal fade" id="asset-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Employee Assets</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="assets_block"></div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
        </div>
      </div>
    </div>
  </div>
@stop

@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
  
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
<script>

</script>
@stop
