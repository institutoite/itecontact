<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Models\Contact;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;




class ContactController extends Controller
{
    public function index(Request $request)
    {
        $query = Contact::query();
        
        // Apply filters if provided
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('user_id')) {
            $query->where('created_by', $request->user_id);
        }
        
        $contacts = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('contacts.index', compact('contacts'));
    }

    /**
     * Show the form for creating a new contact.
     */
    public function create()
    {
        return view('contacts.create');
    }

    /**
     * Store a newly created contact in storage.
     */

     public function store(Request $request)
     {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|min:8|max:15',
            'observations' => 'nullable|string'
        ], [
            'name.required' => 'El nombre es obligatorio',
            'name.max' => 'El nombre no debe exceder los 255 caracteres',
            'phone.required' => 'El teléfono es obligatorio',
            'phone.min' => 'El teléfono debe tener al menos 8 dígitos',
            'phone.max' => 'El teléfono no debe exceder los 15 dígitos'
        ]);

         // Crear el contacto
        $contact = Contact::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'observations' => $validated['observations'] ?? null,
            // 'user_id' => auth()->id(),
            'user_id' => 1,
            'status' => 'No Registrado'
        ]);
 
         // Generar URL para descargar VCF
         $vcfUrl = route('contacts.download.vcf', $contact);
 
         return response()->json([
             'success' => true,
             'message' => 'Contacto guardado exitosamente',
             'vcf_url' => $vcfUrl,
             'contact' => [
                 'id' => $contact->id,
                 'name' => $contact->name,
                 'phone' => $contact->phone
             ]
         ]);
     }
 
     public function downloadVCF(Contact $contact)
     {
         $vcfContent = $this->generateVCF($contact);
         $filename = "contacto_{$contact->name}.vcf";
        //$filename = preg_replace('/[^a-z0-9_\-]/i', '_', $filename);
 
         return response($vcfContent)
             ->header('Content-Type', 'text/vcard')
             ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
     }
 
     private function generateVCF(Contact $contact)
     {
         $vcf = "BEGIN:VCARD\n";
         $vcf .= "VERSION:3.0\n";
         $vcf .= "FN:{$contact->name}\n";
         $vcf .= "TEL;TYPE=CELL:{$contact->phone}\n";
         
         if ($contact->observations) {
             $vcf .= "NOTE:{$contact->observations}\n";
         }
         
         $vcf .= "REV:" . now()->toISOString() . "\n";
         $vcf .= "END:VCARD";
 
         return $vcf;
     }
    // public function downloadVCF(Contact $contact)
    // {
    //     $vcfContent = $this->generateVCF($contact);
    //     $filename = "contacto_{$contact->id}.vcf";
    
    //     return Response::make($vcfContent)
    //         ->header('Content-Type', 'text/vcard')
    //         ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    // }      

    /**
     * Display the specified contact.
     */
    public function show(Contact $contact)
    {
        $whatsappUrl = "https://wa.me/" . $contact->phone;
        return view('contacts.show', compact('contact', 'whatsappUrl'));
    }

    /**
     * Show the form for editing the specified contact.
     */
    public function edit(Contact $contact)
    {
        return view('contacts.edit', compact('contact'));
    }

    /**
     * Update the specified contact in storage.
     */
    public function update(Request $request, Contact $contact)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|regex:/^[0-9]{8,15}$/',
            'name' => 'required|string|max:255',
            'observations' => 'nullable|string',
            'status' => 'required|in:Registrado,No Registrado',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $contact->update($request->all());

        return redirect()->route('contacts.index')
            ->with('success', 'Contacto actualizado exitosamente');
    }

    /**
     * Remove the specified contact from storage.
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();

        return redirect()->route('contacts.index')
            ->with('success', 'Contacto eliminado exitosamente');
    }

    /**
     * Export contacts as VCF files.
     */
    public function exportVcf(Request $request)
    {
        $query = Contact::query();
        
        // Apply filters if provided
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('user_id')) {
            $query->where('created_by', $request->user_id);
        }
        
        $contacts = $query->get();
        
        $vcfContent = '';
        foreach ($contacts as $contact) {
            $vcfContent .= "BEGIN:VCARD\r\n";
            $vcfContent .= "VERSION:3.0\r\n";
            $vcfContent .= "FN:" . $contact->name . "\r\n";
            $vcfContent .= "TEL;TYPE=CELL:" . $contact->phone . "\r\n";
            $vcfContent .= "NOTE:" . $contact->observations . "\r\n";
            $vcfContent .= "END:VCARD\r\n";
        }
        
        $headers = [
            'Content-Type' => 'text/x-vcard',
            'Content-Disposition' => 'attachment; filename="contacts.vcf"',
        ];
        
        return response($vcfContent, 200, $headers);
    }

}
