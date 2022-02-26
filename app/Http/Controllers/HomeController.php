<?php

namespace App\Http\Controllers;

use App\Models\Groups;
use App\Services\GroupService;
use Hamcrest\Core\HasToString;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Storage;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
    {
        $cidades = Storage::disk('local')->get('\capitais.json');
        return view('home')->with('cidades', json_decode($cidades, true));
    }

    public function create(Request $request)
    {
        if ($request->filled('groupName')) {
            $groupName = $request->groupName;
        } else {
            return back()->with('fail', 'Insira o nome do grupo!');
        }

        if (count(DB::table('citygroup')->where('username', '=', $request->user()->username)->where('groupname', '=', $request->groupName)->get()) != 0) {
            return back()->with('fail', 'Já existe um grupo com esse nome cadastrado!');
        }

        $request->id = (new GroupService)->getLastGroupId();
        if ($request->id != '1') {
            $id = $request->id->group + 1;
        } else {
            $id = 1;
        }
        
        if ($request->filled('cidade1')) {
            $cidade1 = $request->get('cidade1');
            $query = DB::table('citygroup')->insert([
                'group' => $id,
                'city' => $cidade1,
                'groupName' => $groupName,
                'username' => $request->user()->username
            ]);
        }
        if ($request->filled('cidade2')) {
            $cidade2 = $request->get('cidade2');
            $query = DB::table('citygroup')->insert([
                'group' => $id,
                'city' => $cidade2,
                'groupName' => $groupName,
                'username' => $request->user()->username
            ]);
        }
        if ($request->filled('cidade3')) {
            $cidade3 = $request->get('cidade3');
            $query = DB::table('citygroup')->insert([
                'group' => $id,
                'city' => $cidade3,
                'groupName' => $groupName,
                'username' => $request->user()->username
            ]);
        }
        if ($request->filled('cidade4')) {
            $cidade4 = $request->get('cidade4');
            $query = DB::table('citygroup')->insert([
                'group' => $id,
                'city' => $cidade4,
                'groupName' => $groupName,
                'username' => $request->user()->username
            ]);
        }
        if ($request->filled('cidade5')) {
            $cidade5 = $request->get('cidade5');
            $query = DB::table('citygroup')->insert([
                'group' => $id,
                'city' => $cidade5,
                'groupName' => $groupName,
                'username' => $request->user()->username
            ]);
        }
        if ($request->filled('cidade1') || $request->filled('cidade2') || $request->filled('cidade3') || $request->filled('cidade4') || $request->filled('cidade5'))
            return back()->with('success', 'Grupo "' . $groupName . '" inserido com sucesso!');
        else
            return back()->with('fail', 'Insira pelo menos uma cidade para criar um grupo!');
    }

    public function groups(Request $request)
    {
        $groups = DB::table('citygroup')->where('username', '=', $request->user()->username)->orderBy('id')->get();
        $collection = collect($groups);
        $grouped = $collection->groupBy('group');
        return view('groups', ['groups' => $grouped]);
    }

    public function editGroup(Request $request, $id)
    {
        $cidades = Storage::disk('local')->get('\capitais.json');
        $group = DB::table('citygroup')->where('group', '=', $id)->orderBy('id')->get();
        return view('edit', ['group' => $group])->with('cidades', json_decode($cidades, true));
    }

    public function updateGroup(Request $request, $id)
    {
        $cidades = Storage::disk('local')->get('\capitais.json');
        $ids = array_values($request->only('oldCity1', 'oldCity2', 'oldCity3', 'oldCity4', 'oldCity5', 'newCity1', 'newCity2', 'newCity3', 'newCity4', 'newCity5'));
        for ($i = 0; $i < sizeof($ids); $i++) {
            list($itemId, $cityName) = explode("_", $ids[$i]);
            if ($cityName == 'null') {
                DB::table('citygroup')->where('id', $itemId)->delete();
                if (count(DB::table('citygroup')->where('group', $id)->get()) == 0) {
                    return view('home', ['cidades' => json_decode($cidades, true)]);
                }
            } else if ($itemId != 'newItem') {
                DB::table('citygroup')
                    ->where('id', $itemId)
                    ->update([
                        'city' => $cityName,
                        'groupName' => $request->groupName
                    ]);
            } else if ($itemId == 'newItem') {
                DB::table('citygroup')->insert([
                    'group' => $id,
                    'city' => $cityName,
                    'groupName' => $request->groupName,
                    'username' => $request->user()->username
                ]);
            }
        }
        return redirect('/groups')->with(['cidades' => json_decode($cidades, true), 'success' => 'Grupo editado com sucesso!']);
    }

    public function delete(Request $request, $id)
    {
        DB::table('citygroup')->where('group', $id)->delete();
        $groups = DB::table('citygroup')->orderBy('id')->get();
        $collection = collect($groups);
        $grouped = $collection->groupBy('group');
        return back()->with('success', 'Grupo excluido com sucesso!');
    }
}
