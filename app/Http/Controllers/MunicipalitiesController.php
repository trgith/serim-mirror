<?php

namespace App\Http\Controllers;

use DB;
use Carbon;
use Exception;
use App\Models\State;
use App\Models\Region;
use App\Models\Municipality;
use App\Models\Annexed;
use App\Models\AnnexedCatalog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class MunicipalitiesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('auditoria');
    }

    public function getViewMunicipality()
    {

        $muncipalitiesByRegion = DB::table('users as a')
            ->join('municipalities as b', 'b.id', 'a.municipality_id')
            ->join('regions as c', 'c.id', 'b.region_id')
            ->select('b.municipality')
            ->where('a.menuroles', '=', 'user,auditoria')
            ->where('a.id', '=', Auth::user()->id)
            ->get();

        $regions = Region::all('id','region');
        return view('dashboard.organ_control.module_municipalities.municipality', compact('regions'));
    }

    public function updateMunicipality(Request $request, $id)
    {
        $munipalityActive = Municipality::find($id);
        DB::beginTransaction();
        try {
            $munipalityActive->status = 1;
            $munipalityActive->save();
            DB::commit();
            return response()->json(['status' => true, 'data' => $munipalityActive, 'message' => 'Municipio activado correctamente']);
        }catch(Exception $e) {
            DB::rollback();
            return response()->json(['status' => false, 'message' => 'Ha ocurrido un error al activar su municipio']);
        }
    }

    public function getViewMunicipalityForRegion(Request $request, $id)
    {
        $municipalitiesForRegions = DB::table('regions as a')
            ->join('municipalities as b','a.id','b.region_id')
            ->where('b.region_id', '=',  $id)
            ->get();
        return view('dashboard.organ_control.module_municipalities.municipalities', compact('municipalitiesForRegions'));
    }

    public function getViewMunicipalityAnnexed()
    {
        /*
        *
        * ! TODO Se recuperan los anexos de esa dependencia
        *
        */
        $descriptions = ["Plan de Desarrollo Municipal \n", "Estructura Org??nica", "Presupuesto de Ingresos y Egresos, Programa Presupuestario, Informes de Gobierno, Recomendaciones, Plan Anual de Evaluaci??n",
        "Documentaci??n Presentada a la Auditoria Superior del Estado", "Documentaci??n Justificativa y Comprobatoria de la Fuente de Financiamiento de Ingresos de Gesti??n", "Entrega de Informaci??n en Sistemas",
        "Entrega de Cuentas P??blicas", "Entrega de Informes Parciales y Dict??men del Auditor Externo", "Expediente Tributario", "Arqueo de Caja", "Conciliaci??n Bancaria",
        "Relaci??n del ??ltimo Cheque Expedido por cada una de las cuentas bancarias", "Relaci??n Cheques Expedidos por entregar o Transferencias por Realizar a beneficiarios", "Relaci??n de Cancelaci??n de Cuentas Bancarias",
        "Relaci??n padrones actualizados de usuarios y contribuyentes"];

        $annexesCatalog = AnnexedCatalog::all();

        $annexes = Annexed::with('annexed_catalog_annexes', 'areas', 'user')->get();

        /*
        *
        * ! TODO Se recuperan los anexos de esa dependencia
        *
        */

        return view('dashboard.organ_control.module_municipalities.annexed', compact('annexes', 'annexesCatalog'));
    }
}
