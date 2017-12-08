@extends('admin.layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card mb-5">
                    <div class="card-body">
                        <form method="post" action="/language-table/diff">
                            <div class="row">
                                <div class="col-5">
                                    <div class="form-group">
                                        <label>Original language</label>
                                        <select name="original" class="form-control">
                                            @foreach ($languages as $language)
                                                <option value="{{ $language }}">{{ $language }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="form-group">
                                        <label>Compare language</label>
                                        <select name="compare" class="form-control">
                                            @foreach ($languages as $language)
                                                <option value="{{ $language }}">{{ $language }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <button type="submit" class="form-control btn btn-success">Download diff</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                @foreach ($translations as $fileKey => $fileContent)
                    <div class="card mb-5">
                        <div class="card-header">{{ $fileKey }}</div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th class="col-12">Key</th>
                                        @foreach ($languages as $language)
                                            <th>{{ $language }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                @foreach ($fileContent as $translationKey => $langs)
                                    <tr>
                                        <td>{{ $translationKey }}</td>
                                        @foreach ($langs as $langKey => $content)
                                            <td>
                                                @if ($content)
                                                    <i class="fa fa-info-circle text-success" data-toggle="tooltip" title="{{ $content }}" data-placement="top"></i>
                                                @else
                                                    <i class="fa fa-times-circle text-danger"></i>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function($) {
            $('[data-toggle="tooltip"]').tooltip()
        });
    </script>
@endsection
