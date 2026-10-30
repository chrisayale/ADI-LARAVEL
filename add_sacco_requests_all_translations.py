import json

# Read existing files
with open('lang/fr.json', 'r', encoding='utf-8') as f:
    fr = json.load(f)

with open('lang/en.json', 'r', encoding='utf-8') as f:
    en = json.load(f)

# Add new translation keys for all sacco-requests views
new_fr = {
    # approve.blade.php
    'approve_sacco_request': 'Approuver la demande de SACCO',
    'back_to_list': 'Retour à la liste',
    'request_information': 'Informations de la demande',
    'approval_details': 'Détails de l\'approbation',
    'unique_sacco_code': 'Code SACCO unique',
    'leave_empty_for_auto_generate': 'Laissez vide pour générer automatiquement',
    'auto_generate_code_note': 'Si laissé vide, un code unique sera généré automatiquement',
    'gps_coordinates_optional': 'Coordonnées GPS (optionnel)',
    'latitude': 'Latitude',
    'longitude': 'Longitude',
    'gps_coordinates_note': 'Les coordonnées GPS seront utilisées pour localiser le SACCO sur une carte',
    'note': 'Note',
    'approval_creates_auto': 'Lors de l\'approbation, le système créera automatiquement :',
    'official_email_created': 'Un e-mail officiel sous le domaine @adi.com (basé sur le nom du SACCO)',
    'default_password_account': 'Un compte utilisateur avec le mot de par défaut : <code>avec1234</code>',
    'approve_and_create_sacco': 'Approuver et créer le SACCO',

    # reject.blade.php
    'reject_sacco_request': 'Rejeter la demande de SACCO',
    'rejection_reason_title': 'Motif du rejet',
    'rejection_reason': 'Raison du rejet',
    'explain_why_rejected': 'Expliquez pourquoi cette demande est rejetée...',
    'provide_rejection_reason': 'Veuillez fournir une raison pour le rejet.',
    'confirm_rejection': 'Confirmer le rejet',

    # show.blade.php
    'sacco_request_details': 'Détails de la demande de SACCO',
    'official_email': 'Email officiel',
    'not_provided': 'Non renseigné',
    'detailed_address': 'Adresse détaillée',
    'applicant_name': 'Nom du demandeur',
    'applicant_role': 'Fonction / Rôle',
    'applicant_email': 'Email du demandeur',
    'applicant_phone': 'Téléphone du demandeur',
    'review_date': 'Date de révision',
    'reason_description': 'Motif / Description',
}

new_en = {
    # approve.blade.php
    'approve_sacco_request': 'Approve SACCO Request',
    'back_to_list': 'Back to List',
    'request_information': 'Request Information',
    'approval_details': 'Approval Details',
    'unique_sacco_code': 'Unique SACCO Code',
    'leave_empty_for_auto_generate': 'Leave empty to auto-generate',
    'auto_generate_code_note': 'If left empty, a unique code will be generated automatically',
    'gps_coordinates_optional': 'GPS Coordinates (optional)',
    'latitude': 'Latitude',
    'longitude': 'Longitude',
    'gps_coordinates_note': 'GPS coordinates will be used to locate the SACCO on a map',
    'note': 'Note',
    'approval_creates_auto': 'Upon approval, the system will automatically create:',
    'official_email_created': 'An official email under the @adi.com domain (based on SACCO name)',
    'default_password_account': 'A user account with default password: <code>avec1234</code>',
    'approve_and_create_sacco': 'Approve and Create SACCO',

    # reject.blade.php
    'reject_sacco_request': 'Reject SACCO Request',
    'rejection_reason_title': 'Rejection Reason',
    'rejection_reason': 'Rejection Reason',
    'explain_why_rejected': 'Explain why this request is being rejected...',
    'provide_rejection_reason': 'Please provide a reason for rejection.',
    'confirm_rejection': 'Confirm Rejection',

    # show.blade.php
    'sacco_request_details': 'SACCO Request Details',
    'official_email': 'Official Email',
    'not_provided': 'Not provided',
    'detailed_address': 'Detailed Address',
    'applicant_name': 'Applicant Name',
    'applicant_role': 'Applicant Role',
    'applicant_email': 'Applicant Email',
    'applicant_phone': 'Applicant Phone',
    'review_date': 'Review Date',
    'reason_description': 'Reason / Description',
}

fr.update(new_fr)
en.update(new_en)

with open('lang/fr.json', 'w', encoding='utf-8') as f:
    json.dump(fr, f, indent=4, ensure_ascii=False)

with open('lang/en.json', 'w', encoding='utf-8') as f:
    json.dump(en, f, indent=4, ensure_ascii=False)

print('Added translation keys to both files')
print(f'fr.json: {len(fr)} keys')
print(f'en.json: {len(en)} keys')
