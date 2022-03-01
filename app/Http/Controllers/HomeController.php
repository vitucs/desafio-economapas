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
        $cidades = array_filter(array_values($request->only('cidade1', 'cidade2', 'cidade3', 'cidade4', 'cidade5')));

        if (sizeof(array_unique($cidades)) != sizeof($cidades)) {
            return back()->with('fail', 'Você não pode cadastrar cidades repetidas!');
        }

        foreach ($cidades as $cidade) {
            $insertCidades[] = $cidade;
        }

        for ($i = 0; $i < sizeof($insertCidades); $i++) {
            DB::table('citygroup')->insert([
                'group' => $id,
                'city' => $insertCidades[$i],
                'groupName' => $groupName,
                'username' => $request->user()->username
            ]);
        }
        if ($cidades != [])
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
        if ($request->user()->username != $group[0]->username) {
            return redirect('/groups')->with(['cidades' => json_decode($cidades, true), 'fail' => 'Você não pode editar esse grupo!']);
        }
        return view('edit', ['group' => $group])->with('cidades', json_decode($cidades, true));
    }

    public function updateGroup(Request $request, $id)
    {
        $cidades = Storage::disk('local')->get('\capitais.json');
        $ids = array_filter(array_values($request->only('oldCity1', 'oldCity2', 'oldCity3', 'oldCity4', 'oldCity5', 'newCity1', 'newCity2', 'newCity3', 'newCity4', 'newCity5')));
        $cidadesVerification = [];
        
        foreach ($ids as $cidade) {
            list($itemIdVerification, $cityNameVerification) = explode("_", $cidade);
            $cidadesVerification[] = $cityNameVerification;
            $updateCidades[] = $cidade;
        }

        if (sizeof(array_unique($cidadesVerification)) != sizeof($cidadesVerification)) {
            return back()->with('fail', 'Você não pode editar cidades repetidas!');
        }

        for ($i = 0; $i < sizeof($updateCidades); $i++) {
            list($itemId, $cityName) = explode("_", $updateCidades[$i]);

            if ($cityName == 'null') {
                DB::table('citygroup')->where('id', $itemId)->delete();
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

        if (count(DB::table('citygroup')->where('group', $id)->get()) == 0) {
            return redirect('/home')->with(['cidades' => json_decode($cidades, true), 'success' => 'Grupo excluído com sucesso!']);
        }
        return redirect('/groups')->with(['cidades' => json_decode($cidades, true), 'success' => 'Grupo editado com sucesso!']);
    }

    public function delete(Request $request, $id)
    {
        $groups = DB::table('citygroup')->where('group', $id)->get();
        if ($request->user()->username != $groups[0]->username) {
            $cidades = Storage::disk('local')->get('\capitais.json');
            return redirect('/groups')->with(['cidades' => json_decode($cidades, true), 'fail' => 'Você não pode excluir esse grupo!']);
        }
        DB::table('citygroup')->where('group', $id)->delete();
        $collection = collect($groups);
        $grouped = $collection->groupBy('group');
        return back()->with('success', 'Grupo excluido com sucesso!');
    }
}
