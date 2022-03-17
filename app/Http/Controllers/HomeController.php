<?php

namespace App\Http\Controllers;

use App\Models\Groups;
use App\Services\GroupService;
use Illuminate\Http\Request;

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
        $groups = new Groups();
        if ($request->filled('groupName')) {
            $groupName = $request->groupName;
        } else {
            return back()->with('fail', 'Insira o nome do grupo!');
        }
        if (count($groups->checkGroupNameByUser($request->user()->username, $request->groupName)) != 0) {
            return back()->with('fail', 'Já existe um grupo com esse nome cadastrado!');
        }

        $request->id = $groups->getLastGroupId();

        if (intval($request->id->group) != 1) {
            $groupId = intval($request->id->group) + 1;
        } else {
            $groupId = 1;
        }
        $cidades = array_filter(array_values($request->only('cidade1', 'cidade2', 'cidade3', 'cidade4', 'cidade5')));

        if (sizeof(array_unique($cidades)) != sizeof($cidades)) {
            return back()->with('fail', 'Você não pode cadastrar cidades repetidas!');
        }

        foreach ($cidades as $cidade) {
            $insertCidades[] = $cidade;
        }

        for ($i = 0; $i < sizeof($insertCidades); $i++) {
            $groups->insertRegistry($groupId, $insertCidades[$i], $groupName, $request->user()->username);
        }

        if ($cidades != [])
            return back()->with('success', 'Grupo "' . $groupName . '" inserido com sucesso!');
        else
            return back()->with('fail', 'Insira pelo menos uma cidade para criar um grupo!');
    }

    public function groups(Request $request)
    {
        $groups = new Groups();
        $collection = collect($groups->searchGroupsByUser($request->user()->username));
        $grouped = $collection->groupBy('group');
        return view('groups', ['groups' => $grouped]);
    }

    public function editGroup(Request $request, $id)
    {
        $cidades = Storage::disk('local')->get('\capitais.json');
        $groups = new Groups();
        $group = $groups->editById($id);
        if ($request->user()->username != $group[0]->username) {
            return redirect('/groups')->with(['cidades' => json_decode($cidades, true), 'fail' => 'Você não pode editar esse grupo!']);
        }
        return view('edit', ['group' => $group])->with('cidades', json_decode($cidades, true));
    }

    public function updateGroup(Request $request, $id)
    {
        $groups = new Groups();
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
                $groups->deleteById($itemId);
            } else if ($itemId != 'newItem') {
                $groups->updateById($itemId, $cityName, $request->groupName);
            } else if ($itemId == 'newItem') {
                $groups->insertRegistry($id, $cityName, $request->groupName, $request->user()->username);
            }
        }
        
        if (count($groups->searchGroupsByGroupId($id)) == 0) {
            return redirect('/home')->with(['cidades' => json_decode($cidades, true), 'success' => 'Grupo excluído com sucesso!']);
        }
        return redirect('/groups')->with(['cidades' => json_decode($cidades, true), 'success' => 'Grupo editado com sucesso!']);
    }

    public function delete(Request $request, $id)
    {
        $groups = new Groups();
        $search = $groups->searchGroupsByGroupId($id);
        if ($request->user()->username != $search[0]->username) {
            $cidades = Storage::disk('local')->get('\capitais.json');
            return redirect('/groups')->with(['cidades' => json_decode($cidades, true), 'fail' => 'Você não pode excluir esse grupo!']);
        }
        $delete = $groups->deleteByGroupId($id);
        $collection = collect($delete);
        return back()->with('success', 'Grupo excluido com sucesso!');
    }
}
