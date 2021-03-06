<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class MenusTableSeeder extends Seeder
{
    private $menuId = null;
    private $dropdownId = array();
    private $dropdown = false;
    private $sequence = 1;
    private $joinData = array();
    private $adminRole = null;
    private $userRole = null;
    private $organ_control = null;
    private $subFolder = '';

    public function join($roles, $menusId){
        $roles = explode(',', $roles);
        foreach($roles as $role){
            array_push($this->joinData, array('role_name' => $role, 'menus_id' => $menusId));
        }
    }

    /*
        Function assigns menu elements to roles
        Must by use on end of this seeder
    */
    public function joinAllByTransaction(){
        DB::beginTransaction();
        foreach($this->joinData as $data){
            DB::table('menu_role')->insert([
                'role_name' => $data['role_name'],
                'menus_id' => $data['menus_id'],
            ]);
        }
        DB::commit();
    }

    public function insertLink($roles, $name, $href, $icon = null){
        $href = $this->subFolder . $href;
        if($this->dropdown === false){
            DB::table('menus')->insert([
                'slug' => 'link',
                'name' => $name,
                'icon' => $icon,
                'href' => $href,
                'menu_id' => $this->menuId,
                'sequence' => $this->sequence
            ]);
        }else{
            DB::table('menus')->insert([
                'slug' => 'link',
                'name' => $name,
                'icon' => $icon,
                'href' => $href,
                'menu_id' => $this->menuId,
                'parent_id' => $this->dropdownId[count($this->dropdownId) - 1],
                'sequence' => $this->sequence
            ]);
        }
        $this->sequence++;
        $lastId = DB::getPdo()->lastInsertId();
        $this->join($roles, $lastId);
        $permission = Permission::where('name', '=', $name)->get();
        if(empty($permission)){
            $permission = Permission::create(['name' => 'visit ' . $name]);
        }
        $roles = explode(',', $roles);
        if(in_array('user', $roles)){
            $this->userRole->givePermissionTo($permission);
        }
        if(in_array('admin', $roles)){
            $this->adminRole->givePermissionTo($permission);
        }
        return $lastId;
    }

    public function insertTitle($roles, $name){
        DB::table('menus')->insert([
            'slug' => 'title',
            'name' => $name,
            'menu_id' => $this->menuId,
            'sequence' => $this->sequence
        ]);
        $this->sequence++;
        $lastId = DB::getPdo()->lastInsertId();
        $this->join($roles, $lastId);
        return $lastId;
    }

    public function beginDropdown($roles, $name, $icon = ''){
        if(count($this->dropdownId)){
            $parentId = $this->dropdownId[count($this->dropdownId) - 1];
        }else{
            $parentId = null;
        }
        DB::table('menus')->insert([
            'slug' => 'dropdown',
            'name' => $name,
            'icon' => $icon,
            'menu_id' => $this->menuId,
            'sequence' => $this->sequence,
            'parent_id' => $parentId
        ]);
        $lastId = DB::getPdo()->lastInsertId();
        array_push($this->dropdownId, $lastId);
        $this->dropdown = true;
        $this->sequence++;
        $this->join($roles, $lastId);
        return $lastId;
    }

    public function endDropdown(){
        $this->dropdown = false;
        array_pop( $this->dropdownId );
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* Get roles */
        $this->adminRole = Role::where('name' , '=' , 'admin' )->first();
        $this->userRole = Role::where('name', '=', 'user' )->first();
        $this->auditoria = Role::where('name', '=', 'auditoria')->first();

        /* Create Sidebar menu */
        DB::table('menulist')->insert([
            'name' => 'sidebar menu'
        ]);

        $this->menuId = DB::getPdo()->lastInsertId();  //set menuId
        //$this->insertLink('guest,user,admin', 'Dashboard', '/', 'cil-speedometer');
        $this->beginDropdown('admin', 'Configuraci??n', 'cil-calculator');
            $this->insertLink('admin', 'Notas',                   '/notes');
            $this->insertLink('admin', 'Registro de Administradores',                    '/users');
            $this->insertLink('admin', 'Editar menu',               '/menu/menu');
            $this->insertLink('admin', 'Editar elementos del menu',      '/menu/element');
            $this->insertLink('admin', 'Editar roles',              '/roles');
        $this->endDropdown();
        $this->insertLink('guest', 'Login', '/login', 'cil-account-logout');


        /* Create top menu */
        DB::table('menulist')->insert([
            'name' => 'sidebar menu'
        ]);

        /**
         * !Men?? Administrador
         */
        $this->menuId = DB::getPdo()->lastInsertId();  //set menuId
        $this->beginDropdown('guest,user,admin', 'Paginas');
        $id = $this->insertLink('guest,user,admin', 'Dashboard',    '/');
        //$id = $this->insertLink('user,admin', 'Notes',              '/notes');
        $id = $this->insertLink('admin', 'Usuarios',                   '/users');
        $this->endDropdown();
        $id = $this->beginDropdown('admin', 'Configuraci??n');

        $id = $this->insertLink('admin', 'Editar menu',               '/menu/menu');
        $id = $this->insertLink('admin', 'Editar elementos del menu',      '/menu/element');
        $id = $this->insertLink('admin', 'Editar roles',              '/roles');
        $this->endDropdown();

        /**
         * !Men?? Auditoria
         */
        $id = $this->insertTitle('auditoria', 'M??DULO DE USUARIOS');
        $id = $this->insertLink('auditoria', 'Asignaci??n de Usuarios', '/asignacion_usuarios', 'cil-user');
        $id = $this->insertLink('auditoria', 'Gesti??n de Testigos', '/gestion_testigos', ' cil-people');

        $id = $this->insertTitle('auditoria', 'CONTROL DE REGIONES');
        $id = $this->insertLink('auditoria', 'Control de Regiones', '/control_regiones', 'cil-institution');

/*      $id = $this->insertTitle('auditoria', 'M??DULO DE DEPENDENCIAS');
        $id = $this->insertLink('auditoria', 'Gesti??n de Dependencias', '/gestion_dependencias', 'cil-home');
 */
        $id = $this->insertTitle('auditoria', 'M??DULO DE ANEXOS');
        $id = $this->insertLink('auditoria', 'Anexos', '/anexos', 'cil-file');


        /**
         * !Men?? Contraloria
         */

        $id = $this->insertTitle('contraloria', 'M??DULO CONTRALORIA');

        $id = $this->insertLink('contraloria', 'Asignaci??n de Dependencias', '/gestion_dependencias', 'cil-home');
        $id = $this->insertLink('contraloria', 'Control de Dependencias', '/control_dependencias', 'cil-home');


        $id = $this->insertLink('contraloria', 'Subir Imagen', '/subir_imagen', 'cil-file');

        $id = $this->insertTitle('contraloria', 'M??DULO ASIGNACI??N');
        $id = $this->insertLink('contraloria', 'Asignaci??n de Anexos', '/asignacion_anexos', 'cil-file');

        $this->joinAllByTransaction(); ///   <===== Must by use on end of this seeder
    }
}
