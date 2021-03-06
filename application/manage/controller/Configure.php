<?php
namespace app\manage\controller;

use app\common\model\ConfigureModel;
use app\common\tool\Tool;
use think\Config;

class Configure extends BasicController
{
    private $id;
    private $validate;
    private $url        = 'configure/index';
    private $groups     = '';

    public function __construct()
    {
        $this->groups   = lang('configure groups');


        parent::__construct();
        $this->id       = !empty($this->request->param('id')) ? intval($this->request->param('id')) : $this->id;
        $this->validate = [
            ['title|标题', 'require'],
            ['configure_name|配置名称', 'require|unique:configure,configure_name,'.$this->id.',configure_id']
        ];

        $nav = [
            '配置属性列表' => ['url' => 'configure/index'],
            '配置属性增加' => ['url' => 'configure/add'],
            '配置属性修改' => ['url' => ['configure/edit', ['id' => $this->id]], 'style' => "display: none;"],
        ];
        $this->assign('navTabs',  parent::navTabs($nav));
    }

    /**
     * 配置变量列表
     */
    public function index()
    {
        $where  = [];
        $groups   = intval($this->request->get('groups'));

        if(!empty($groups)){
            $where['groups']  = $groups;
        }

        $list = ConfigureModel::where($where)->paginate();

        return $this->fetch('',[
            'list'  => $list,
            'page'  => $list->render()
        ]);
    }

    /**
     * 配置变量添加
     */
    public function add()
    {
        //add_post 数据处理
        if($this->request->isPost()){
            $post = $this->request->post();

            //数据验证
            $result = $this->validate($post,$this->validate);
            if($result !== true){
                return $this->error($result);
            }

            //写入数据
            if(ConfigureModel::create($post)){
                return $this->success(lang('Add success'),$this->url);
            }else{
                return $this->error(lang('Add error'));
            }
        }

        return $this->fetch('',[
            'info'  => ''
        ]);
    }

    /**
     * 配置变量修改
     */
    public function edit()
    {
        $info = ConfigureModel::get($this->id);
        if(empty($info)){
            return abort(404, lang('404 not found'));
        }

        return $this->fetch('',[
            'info'  => $info
        ]);
    }

    /**
     * 配置变量更新数据
     */
    public function update()
    {
        //edit_post 数据处理
        if($this->request->isPost()){
            $post   = $this->request->post();

            //数据验证
            $result = $this->validate($post,$this->validate);
            if($result !== true){
                return $this->error($result);
            }

            //查询数据
            $query  = ConfigureModel::get($this->id);
            if(empty($query)){
                return abort(404, lang('404 not found'));
            }

            //修改数据库
            if($query->save($post)){
                return $this->success(lang('Edit success'),$this->url);
            }else{
                return $this->error(lang('Edit failed'));
            }
        }
        return abort(404, lang('404 not found'));
    }

    /**
     * 配置变量删除
     */
    public function delete()
    {
        $delete = ConfigureModel::get($this->id);

        if(!$this->request->isAjax() || empty($delete)){
            return abort(404, lang('404 not found'));
        }

        //数据删除
        if($delete->delete()){
            return $this->success(lang('Delete success'),url($this->url));
        }else{
            return $this->error(lang('Delete failed'));
        }
    }

    /**
     * 基本配置
     */
    public function basicSettings()
    {

        $list       = ConfigureModel::where(['groups' =>1])->select();
        $fileName   = 'basic';

        return $this->fetch('config',[
            'html'  => self::htmlBuilder($list,$fileName),
            'info'  => [
                'class_name'    =>array_get($this->groups , 1),
                'file_name'     => $fileName
            ]
        ]);
    }

    /**
     * 邮箱配置
     */
    public function emailSettings()
    {

        $list       = ConfigureModel::where(['groups' => 2])->select();
        $fileName   = 'email';

        return $this->fetch('config',[
            'html'  => self::htmlBuilder($list,$fileName),
            'info'  => [
                'class_name'    => array_get($this->groups , 2),
                'file_name'     => $fileName
            ]
        ]);
    }

    /**
     * 生成配置文件
     * push   /config/extra
     */
    public function configBuilder()
    {
        //生成配置文件数据处理
        if($this->request->isPost()){
            $post   = $this->request->post();
            $pash   = CONF_PATH.'extra'.DS.$post['file_name'].EXT;
            $txt    = '

        //'.$post['class_name'].'

        ';

            $list   = ConfigureModel::where(['groups'=>array_search($post['class_name'], $this->groups)])->select();
            if(empty($list)){
                return $this->error('配置属性组数据找不到');
            }
            $tool   = Tool::get('file');

            //重组数组阵列
            foreach ($list as $v) {
                $configureName = isset($post[$v['configure_name']])?$post[$v['configure_name']]:'';
                if(is_array($configureName)){
                    $data   = $tool->recombinantArray($configureName,$v['configure_value']);
                }else{
                    $data   = "'{$configureName}'";
                }
                $txt.="'".$v['configure_name']."'  =>  $data, //".$v['title'].'
        ';
            }

            //写入到配置config/extr/目录下
            $content = '<?php
return ['.$txt.'];
?>';
            $thisContent = $tool->writeFile($pash,$content);
            if ($thisContent){
                return $this->success(lang('edit success'));
            }else{
                return $this->error('更改失败,请检查网站目录'.$pash.'权限');
            }
        }
        return abort(404, lang('404 not found'));

    }


    /**
     * 配置阵列生成html
     *
     * @param  object  $list
     * @param  string  $name
     * @return string
     */
    private function htmlBuilder($list, $name)
    {
        $html       = '';
        $formType   = lang('form type');

        //循环每个配置变量 生成html
        foreach($list as $v){

            //使用表单枚举生成<form> 标签支持
            $input  =  Tool::get('helper')->formEnum(
                $v['form_type'],          //表单类型
                $v['configure_name'],                           //配置变量名称
                Config::get($name.'.'.$v['configure_name']),    //读取配置变量的值
                ['class' => 'form-control text'],               //其他属性
                json_decode($v['configure_value'])              //需要生成多个 如select
            );

            //写入$html变量里
            $html   .= "<tr>
                            <th width='100'>{$v['title']}</th>
                            <th>
                                {$input}<span style='padding-left: 10px'>{$v['comment']}</span>
                            </th>
                        </tr>";
        }

        return $html;
    }
}