@extends('layout.admin')
@section('content')
<div class="col-sm-6 animated fadeInRight">
    <div class="ibox">
        <div class="ibox-title">
            <h5>角色管理</h5>
        </div>
        <div class="ibox-content">
            <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover text-nowrap bg-white text-center">
                <tr>
                    {{--<td width="30"><input type="checkbox" name="" id="" class="i-checks"></td>--}}
                    <td width="50">编号</td>
                    <td>组名</td>
                    {{--<td>权限</td>--}}
                    <td>状态</td>
                    <td width="120">操作</td>
                </tr>
                @if($lists->count())
                    @foreach($lists as $k=>$v)
                        <tr>
                            {{--<td width="30"><input type="checkbox" name="" id="" class="i-checks"></td>--}}
                            <td>{{ $v->id }}</td>
                            <td>{{ $v->name }}</td>
                            {{--<td>--}}
                                {{--@if (count($v->roleAccess))--}}
                                    {{--@foreach ($v->roleAccess as $vm)--}}
                                        {{--<span class="label label-primary">--}}
                                        {{--{{ $vm['name'] }}--}}
                                        {{--</span>&nbsp;--}}
                                    {{--@endforeach--}}
                                {{--@endif--}}
                            {{--</td>--}}
                            <td>{!! colorText($v->status, '正常', '关闭') !!}</td>
                            <td>
                                <a class="btn btn-sm btn-info" href="{{ editURL('admin.roles.edit', $v->id) }}">编辑</a>
                                <button class="btn btn-sm btn-danger" onclick="Delete('{{ editURL('admin.roles.destroy', $v->id) }}')">删除</button>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4">
                            暂无数据
                        </td>
                    </tr>
                @endif
            </table>
            </div>
            <a href="{{ route('admin.roles.create') }}" class="btn btn-info">添加角色</a>
            <div class="text-center">
                @if($lists->count())
                    {!! $lists->render() !!}
                @endif
            </div>
        </div>
    </div>
</div>
<script>
    var createModal = '#createModal';

    function AddChild(id) {
        var json = $('#edit_' + id).attr('data');
        json = JSON.parse(json);
        $(createModal).find('select[name=pid]').val(json.id);
        $(createModal).find('input[name=prefix]').val(json.prefix);
        $(createModal).modal('show');
    }
</script>

@include('admin.modal.delete')

@endsection