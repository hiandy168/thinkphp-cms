<?php
use app\common\tool\Tool;
?>
<div class="bs-example">
    <table class="table table-bordered">
        <tr>
            <th>标题</th>
            <th>
                <input class="form-control text" type="text" name="title" value="{$info.title ?? ''}">
                <span class="form-required">*</span>
            </th>
        </tr>
        <tr>
            <th width="150">所属模型</th>
            <td>
               <?=Tool::get('form')->select('group', $info['groups'], object_get($info,'group'), ['class' => 'form-control text'])?>
                <span class="form-required">*</span>
            </td>
        </tr>
        <tr>
            <th width="150">所属分类</th>
            <td>
               <?=Tool::get('form')->select('type', $info['types'], object_get($info,'type'), ['class' => 'form-control text'])?>
                <span class="form-required">*</span>
            </td>
        </tr>
        <tr>
            <th>模版文件</th>
            <th>
                <div class="input-group text">
                    <input type="text" class="form-control" name="template_file"  value="{$info.template_file ?? ''}">
                    <span class="input-group-addon">.php</span>
                </div>
                <span class="form-required">*</span>
                <span class="span-text">只能是英文名 数字 下划线</span>
            </th>
        </tr>
    </table>
</div>

<div class="form-actions col-sm-12">
    <button class="btn btn-primary js-ajax-submit" type="submit">提交</button>
    <button type="button" class="btn btn-primary ajax-post " autocomplete="off">
        保存
    </button>
    <a class="btn btn-default active" href="JavaScript:history.go(-1)">返回</a>

</div>


