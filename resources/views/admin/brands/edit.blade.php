@extends('admin.layouts.app')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Brand</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('brands.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <form action="" id="editBrandForm" name="editBrandForm" method="post">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Name" value="{{ $brand->name }}">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="slug">Slug</label>
                                <input type="text" readonly name="slug" id="slug" class="form-control" placeholder="Slug" value="{{ $brand->slug }}">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status">Status</label>
                                <select name="status" id="Status" class="form-control">
                                    <option {{ ($brand->status == 1) ? 'selected' : '' }} value="1">Active</option>
                                    <option {{ ($brand->status == 0) ? 'selected' : '' }} value="0">Block</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('brands.index')}}" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </form>
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->
@endsection

@section('customJs')
<script>
    // $('#editBrandForm').submit(function(event) {
    //     event.preventDefault();

    //     var element = $(this);
    //     $('button[type=submit]').prop('disabled', true);
    //     $.ajax({
    //         url: '{{ route("brands.update", $brand->id) }}',
    //         type: 'put',
    //         data: element.serializeArray(),
    //         dataType: 'json',
    //         success: function(res) {
    //             $('button[type=submit]').prop('disabled', false);
    //             if (res["status"] == true) {
    //                 window.location.href = "{{ route('brands.index') }}";
    //                 $('#name').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
    //                 $('#slug').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");

    //             } else {

    //                 if (res['notFound'] == true) {
    //                     window.location.href="{{ route('brands.index') }}"
    //                 }
    //                 var errors = res['errors'];
    //                 if (errors['name']) {
    //                     $('#name').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['name']);
    //                 } else {
    //                     $('#name').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
    //                 }

    //                 if (errors['slug']) {
    //                     $('#slug').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['slug']);
    //                 } else {
    //                     $('#slug').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
    //                 }
    //             }

    //         },
    //         error: function(jqXHR, exception) {
    //             console.log('Something went wrong');
    //         }
    //     })
    // });
    
    document.getElementById('editBrandForm').addEventListener('submit', function(event) {
    event.preventDefault();

    var element = this;
    document.querySelector('button[type=submit]').disabled = true;
    url = '{{ route("brands.update", $brand->id) }}';
    fetch(url, {
        method: 'PUT',
        body: new URLSearchParams(new FormData(element)),
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(res => {
        document.querySelector('button[type=submit]').disabled = false;
        if (res.status === true) {
            window.location.href = '{{ route('brands.index') }}';
            document.getElementById('name').classList.remove('is-invalid');
            document.querySelector('#name + p').classList.remove('invalid-feedback');
            document.querySelector('#name + p').innerHTML = '';
            document.getElementById('slug').classList.remove('is-invalid');
            document.querySelector('#slug + p').classList.remove('invalid-feedback');
            document.querySelector('#slug + p').innerHTML = '';
        } else {
            if (res.notFound === true) {
                window.location.href = '{{ route('brands.index') }}';
            }
            var errors = res.errors;
            if (errors.name) {
                document.getElementById('name').classList.add('is-invalid');
                document.querySelector('#name + p').classList.add('invalid-feedback');
                document.querySelector('#name + p').innerHTML = errors.name;
            } else {
                document.getElementById('name').classList.remove('is-invalid');
                document.querySelector('#name + p').classList.remove('invalid-feedback');
                document.querySelector('#name + p').innerHTML = '';
            }

            if (errors.slug) {
                document.getElementById('slug').classList.add('is-invalid');
                document.querySelector('#slug + p').classList.add('invalid-feedback');
                document.querySelector('#slug + p').innerHTML = errors.slug;
            } else {
                document.getElementById('slug').classList.remove('is-invalid');
                document.querySelector('#slug + p').classList.remove('invalid-feedback');
                document.querySelector('#slug + p').innerHTML = '';
            }
        }
    })
    .catch(error => {
        console.log('Something went wrong');
    });
});


    $('#name').change(function() {

        var element = $(this);
        $('button[type=submit]').prop('disabled', true);
        $.ajax({
            url: '{{ route("getslug") }}',
            type: 'get',
            data: {
                title: element.val()
            },
            dataType: 'json',
            success: function(res) {
                $('button[type=submit]').prop('disabled', false);
                if (res['status'] == true) {
                    $('#slug').val(res['slug'])
                }
            }
        });
    });
</script>
@endsection