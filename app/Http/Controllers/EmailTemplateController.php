<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Storage;


class EmailTemplateController extends Controller
{
    /**
     * Muestra la página de edición de plantillas de correos
     *
     * @return \Illuminate\View\View
     */
    public function edit($templateId = null)
    {
        $templates = EmailTemplate::all();
        $template = $templateId ? EmailTemplate::findOrFail($templateId) : $templates->first();
        return view('content.e-commerce.backoffice.email-templates', compact('template', 'templates'));
    }

    /**
     * Actualiza la plantilla de correo
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $templateId)
    {
        $request->validate([
            'template_subject' => 'required',
            'template_body' => 'required',
        ]);

        $template = EmailTemplate::findOrFail($templateId);
        $template->update([
            'subject' => $request->template_subject,
            'body' => $request->template_body,
        ]);

        return redirect()->route('email-templates.edit', $template->id)->with('success', 'Plantilla actualizada correctamente.');
    }

    /**
     * Maneja la carga de imágenes para el editor Quill
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadImage(Request $request)
    {
        if($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            return response()->json(['url' => Storage::url($path)]);
        }
        return response()->json(['error' => 'No se ha proporcionado ninguna imagen.'], 400);
    }
}
