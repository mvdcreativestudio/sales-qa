<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompanySettings;
use App\Http\Requests\UpdateCompanySettingsRequest;
use Illuminate\Support\Facades\Log;

class CompanySettingsController extends Controller
{
    public function index()
    {
      $companySettings = CompanySettings::firstOrFail();
      return view('company-settings.index', compact('companySettings'));
    }

    public function update(UpdateCompanySettingsRequest $request)
    {
        Log::debug('Request reached controller', ['data' => $request->all()]);

        $validatedData = $request->validated();
        Log::debug('Validated data', ['data' => $validatedData]);

        $companySettings = CompanySettings::firstOrFail();
        $companySettings->update($validatedData);

        return redirect()->route('company-settings.index')->with('success', 'Configuración actualizada correctamente.');
    }



}
