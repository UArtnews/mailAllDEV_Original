<?php

class AdminController extends BaseController {

    public function index($data)
    {
        $data = array(
            'default_tweakables'    => reindexArray(DefaultTweakable::all(), 'parameter', 'value'),
            'tweakables'            => array(),
            'action'                => null,
        );
        return View::make('admin.index', $data);
    }

    public function users($data){
        $instances = array(0 => 'GLOBAL');

        foreach(Instance::all() as $instance){
            $instances[$instance->id] = $instance->name;
        }

        $data['users'] = User::with('permissions')->where('submitter', 0)->get();
        $data['deleted_users'] = User::onlyTrashed()->where('submitter', 0)->get();
        $data['instances'] = $instances;
        $data['nodes'] = array(
            'edit' => 'edit',
            'admin' => 'admin',
            'superAdmin' => 'superAdmin');

        return View::make('admin.users', $data);
    }

    public function instances($data){
        $data = array(
            'default_tweakables'    => reindexArray(DefaultTweakable::all(), 'parameter', 'value'),
            'tweakables'            => array(),
            'action'                => 'instances',
            'instances'             => Instance::all(),
            'deleted_instances'     => Instance::onlyTrashed()->get()
        );

        return View::make('admin.instances', $data);
    }

    public function save($data){
        //Handle Incoming PermissionNodes
        if($data['subAction'] == 'permissionNode'){
            if(UserPermission::where('instance_id', Input::get('instance_id', Input::get('instance_id')))->where('user_id', Input::get('user_id'))->where('node', Input::get('node'))->count() > 0){
                //Just leave it alone!
                return Redirect::back()->withMessage('Permission Node Updated!');
            }else{
                UserPermission::create(Input::all());
                return Redirect::back()->withMessage('Permission Node Added!');
            }
        }elseif($data['subAction'] == 'user'){
            $validator = Validator::make(Input::all(), User::$rules);
            if($validator->fails()){
                return Redirect::back()->withErrors($validator, 'newUser');
            }else{
                //Do the insert
                if(User::where('uanet', Input::get('uanet'))->count() > 0){
                    return Redirect::back()->withMessage('User already exists!');
                }else{
                    User::create(Input::all());
                    return Redirect::back()->withMessage('User succesfully Added!');
                }
            }
        }elseif($data['subAction'] == 'instance'){
            if(Instance::where('name', Input::get('name'))->count() > 0){
                return Redirect::back()->withError('An instance with that name already exists');
            }else {
                Instance::create(array('name' => Input::get('name')));
                return Redirect::back()->withMessage('Instance Successfully Created!');
            }
        }
    }

    public function delete($data){
        //Handle Incoming PermissionNodes
        if($data['subAction'] == 'permissionNode'){
            UserPermission::find($data['id'])->delete();
            return Redirect::back()->withMessage('Permission Node Deleted');
        }elseif($data['subAction'] == 'user'){
            User::find($data['id'])->delete();
            UserPermission::where('user_id', $data['id'])->delete();
            return Redirect::back()->withMessage('User successfully Deleted!');
        }elseif($data['subAction'] == 'instance'){
            Instance::find($data['id'])->delete();
            return Redirect::back()->withMessage('Instance successfully Deleted!');
        }
    }

    public function restore($data){
        if($data['subAction'] == 'instance'){
            Instance::onlyTrashed()->where('id', $data['id'])->restore();
            return Redirect::back()->withmessage('Instance Successfully Restored!');
        }elseif($data['subAction'] == 'user'){
            User::onlyTrashed()->where('id', $data['id'])->restore();
            return Redirect::back()->withMessage('User Successfully Restored');
        }
    }
}