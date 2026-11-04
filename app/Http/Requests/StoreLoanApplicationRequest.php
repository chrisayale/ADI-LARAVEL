<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLoanApplicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // user_id is required for admin (creating for member), but not for member (uses auth()->id())
        $rules = [
            'amount_applied' => 'required|numeric|min:10000|max:10000000',
            'purpose' => 'required|string|max:1000',
            'has_collateral' => 'nullable|boolean',
            'collateral_type' => 'required_if:has_collateral,1,true|nullable|string|max:255',
            'collateral_description' => 'required_if:has_collateral,1,true|nullable|string',
            'collateral_value' => 'required_if:has_collateral,1,true|nullable|numeric|min:0',
            'guarantors' => 'required|array|min:1|max:3',
            'guarantors.*.name' => 'required|string|max:255',
            'guarantors.*.phone' => 'required|string|max:20',
            'guarantors.*.email' => 'nullable|email|max:255',
            'guarantors.*.id_number' => 'required|string|max:50',
            'guarantors.*.occupation' => 'nullable|string|max:100',
            'guarantors.*.address' => 'nullable|string',
            'guarantors.*.relationship' => 'nullable|string|max:100',
            'documents' => 'nullable|array|max:10',
            'documents.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:15360',
            'apply_late_penalty' => 'boolean',
            'late_penalty_amount' => 'nullable|numeric|min:0',
            'adjusted_daily_amount' => 'nullable|numeric|min:1',
            'theoretical_daily_amount' => 'nullable|numeric|min:0',
            'total_surplus_amount' => 'nullable|numeric|min:0',
            'surplus_type' => 'nullable|in:surplus,shortfall,none',
        ];

        // Add user_id rule only for admin routes
        if ($this->route()->getPrefix() === 'admin') {
            $rules['user_id'] = 'required|exists:users,id';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'user_id.required' => 'Veuillez sélectionner un membre.',
            'user_id.exists' => 'Le membre sélectionné n\'existe pas.',
            'amount_applied.required' => 'Le montant demandé est obligatoire.',
            'amount_applied.numeric' => 'Le montant demandé doit être un nombre.',
            'amount_applied.min' => 'Le montant minimum est de 10 000 CDF.',
            'amount_applied.max' => 'Le montant maximum est de 10 000 000 CDF.',
            'purpose.required' => 'L\'objet du prêt est obligatoire.',
            'purpose.max' => 'L\'objet du prêt ne doit pas dépasser 1000 caractères.',
            'collateral_type.required_if' => 'Le type de garantie est requis lorsque la case "A une Garantie" est cochée.',
            'collateral_description.required_if' => 'La description de la garantie est requise lorsque la case "A une Garantie" est cochée.',
            'collateral_value.required_if' => 'La valeur estimée de la garantie est requise lorsque la case "A une Garantie" est cochée.',
            'guarantors.required' => 'Au moins un garant est requis.',
            'guarantors.min' => 'Au moins un garant est requis.',
            'guarantors.max' => 'Maximum 3 garants autorisés.',
            'guarantors.*.name.required' => 'Le nom du garant est obligatoire.',
            'guarantors.*.phone.required' => 'Le numéro de téléphone du garant est obligatoire.',
            'guarantors.*.id_number.required' => 'Le numéro d\'identité du garant est obligatoire.',
            'documents.*.mimes' => 'Le fichier doit être au format PDF, JPG, JPEG ou PNG.',
            'documents.*.max' => 'La taille du fichier ne doit pas dépasser 15 MB.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Ensure application_number is never passed from request
        $this->request->remove('application_number');
    }
}