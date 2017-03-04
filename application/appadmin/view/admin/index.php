<div class="wrap js-check-wrap">
    <ul class="nav nav-tabs">
        {$navTabs.nav}
    </ul>
    <table class="table table-hover table-bordered">
        <thead>
        <tr>
            <th width="50">ID</th>
            <th>用户名</th>
            <th>最后登录IP</th>
            <th>最后登录时间</th>
            <th>邮箱</th>
            <th width="80">登录权限</th>
            <th width="140">操作</th>
        </tr>
        </thead>
        <tbody>
        {foreach name="list" item="v"}
            <tr>
                <td>{$v.admin_id}</td>
                <td>{$v.admin_name}</td>
                <td>{$v.last_login_ip}</td>
                <td>{$v.last_login_time}</td>
                <td>{$v.admin_email}</td>
                <td>
                    {$v.login_priv}
                    {if condition="$v['login_priv'] == 'Y'"}
                        <a  class="a-post" post-msg="你确定要拉黑吗？" post-url="{:url('admin/privates',['id' => $v['admin_id'],'param'=>1])}"
                    >关闭</a>
                    {else /}
                        <a class="a-post" post-msg="你确定要启用吗？" post-url="{:url('admin/privates',['id' => $v['admin_id'],'param'=>2])}"
                        >启用</a>
                    {/if}
                </td>
                <td>
                    {if condition="$v['admin_id']==1"}
                        <font color="#cccccc">独立权限</font> | <font color="#cccccc">编辑</font> | <font color="#cccccc">删除</font>
                    {else /}
                        <a href="{:url('auth/adminAuthorize',['id' => $v['admin_id'],'name'=>$v['admin_name']])}">独立权限</a> |
                        <a href="{:url('admin/edit',['id' => $v['admin_id']])}">编辑</a> |
                        <a class="a-post" post-msg="你确定要删除吗" post-url="{:url('admin/delete',['id' => $v['admin_id']])}">删除</a>
                    {/if}
                </td>
            </tr>
        {/foreach}
        </tbody>
    </table>
    <div class="text-center">
        {$page}
    </div>
</div>
