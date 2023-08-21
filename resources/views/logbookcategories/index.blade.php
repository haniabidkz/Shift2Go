@extends('layouts.main')

@section('content')
    <!-- Page content -->
    <div class="page-content">
        <!-- Page title -->
        <div class="page-title">
            <div class="row justify-content-between align-items-center">
                <div class="col-md-6 d-flex align-items-center justify-content-between justify-content-md-start mb-3 mb-md-0">
                    <!-- Page title + Go Back button -->
                    <div class="d-inline-block">
                        <h5 class="h4 d-inline-block font-weight-400 mb-0 text-white">Location</h5>
                    </div>
                    <!-- Additional info -->
                </div>
                <div class="col-md-6 d-flex align-items-center justify-content-between justify-content-md-end">
                    <a href="#modal-loacation-create" class="btn btn-sm btn-white btn-icon-only rounded-circle " data-toggle="modal">
                        <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
                    </a>
                </div>
            </div>
        </div>
        <!-- Listing -->
        <div class="mt-4">
            <div class="card">
                <div class="table-responsive">
                    <table class="table align-items-center">
                        <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Time Zone</th>
                            <th scope="col">Managers</th>
                            <th scope="col">Employees</th>
                            <th scope="col"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td> Surat &nbsp;&nbsp; <i class="fas fa-map-marked-alt"></i> </td>
                            <td> London </td>
                            <td>  </td>
                            <td> 70 </td>
                            <td class="text-right">
                                <!-- Actions -->
                                <div class="actions ml-3">
                                    <a href="#" class="action-item mr-2" data-toggle="tooltip" title="Edit">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <a href="#model_location_delete" data-toggle="modal" class="action-item text-danger mr-2 emp_delete">
                                        <i class="fas fa-trash" data-toggle="tooltip" title="Delete"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td> Rajkot </td>
                            <td> Budapest </td>
                            <td>  </td>
                            <td> 50 </td>
                            <td class="text-right">
                                <!-- Actions -->
                                <div class="actions ml-3">
                                    <a href="#" class="action-item mr-2" data-toggle="tooltip" title="Edit">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <a href="#model_location_delete" data-toggle="modal" class="action-item text-danger mr-2 emp_delete">
                                        <i class="fas fa-trash" data-toggle="tooltip" title="Delete"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('model')
    <!-- modal Start -->
    <div class="modal fade" id="model_location_delete" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h6" id="modal_title_6">Are you sure?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="py-3 text-center">
                        <i class="fas fa-exclamation-circle fa-4x"></i>
                        <p> Deleting a location will permanently delete all shifts assigned to it, and cannot be undone. Are you sure you want to delete this location? </p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-primary rounded-pill check-add-user">Ok</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-loacation-create" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-control-label"> Name </label>
                        <input type="text" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-control-label"> TimeZone </label>
                        <select data-placeholder="Select a Timezone" class="form-control js-single-select" required="required" name="timezone">
                            <option value=""></option>
                            <optgroup label="Africa">
                                <option>Abidjan</option>
                                <option>Accra</option>
                                <option>Addis Ababa</option>
                                <option>Algiers</option>
                                <option></option>
                            </optgroup>

                            <optgroup label="America">
                                <option>Adak</option>
                                <option>Anchorage</option>
                                <option>Anguilla</option>
                                <option>Antigua</option>
                                <option>Araguaina</option>
                                <option></option>
                            </optgroup>

                            <optgroup label="Antarctica">
                                <option>Casey</option>
                                <option>Davis</option>
                                <option>DumontDUrville</option>
                                <option></option>
                            </optgroup>
                            <optgroup label="Arctic">
                                <option>Longyearbyen</option>
                                <option></option>
                            </optgroup>
                            <optgroup label="UTC">
                                <option>UTC</option>
                                <option></option>
                            </optgroup>

                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-control-label"> Address </label>
                        <textarea class="form-control autogrow"  rows="1" style="resize: none"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-control-label"> Employee </label>
                        <select data-placeholder="Yours Placeholder" class="form-control js-multiple-select" multiple="multiple" name="employee[]" required="required">
                            <option value="">Select a Employee</option>
                            <option value="1">Employee1</option>
                            <option value="2">Employee2</option>
                            <option value="3">Employee3</option>
                            <option value="4">Employee4</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-primary rounded-pill mr-auto" data-dismiss="modal">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection
