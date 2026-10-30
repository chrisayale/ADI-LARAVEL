import json

# Read existing files
with open('lang/fr.json', 'r', encoding='utf-8') as f:
    fr = json.load(f)

with open('lang/en.json', 'r', encoding='utf-8') as f:
    en = json.load(f)

# Add new translation keys for all sacos views
new_fr = {
    # index.blade.php
    'sacos_list': 'Liste des SACCOs',
    'new_sacco': 'Nouveau SACCO',
    'search_and_filter': 'Rechercher et filtrer',
    'search_by_name_code_registration': "Rechercher par nom, code ou numéro d'enregistrement",
    'all_statuses': 'Tous les statuts',
    'search': 'Rechercher',
    'reset': 'Réinitialiser',
    'no_sacos_found': 'Aucun SACCO trouvé',
    'name': 'Nom',
    'code': 'Code',
    'official_email': 'Email officiel',
    'none': 'Aucun',
    'official_email_auto_generated': 'Email officiel généré automatiquement',
    'actions': 'Actions',
    'view': 'Voir',
    'edit': 'Éditer',
    'delete': 'Supprimer',
    'confirm_delete_sacco': "Êtes-vous sûr de vouloir supprimer ce SACCO ? Cette action est irréversible.",
    'manage_official_account': 'Gérer le compte officiel',

    # create.blade.php
    'sacco_information': 'Informations du SACCO',
    'please_correct_errors': 'Veuillez corriger les erreurs suivantes',
    'sacco_name': 'Nom du SACCO',
    'official_mobile_money': "Numéro Mobile Money officiel du SACCO",
    'where_member_money_deposited': "Numéro où l'argent des membres sera déposé",
    'official_email_optional': 'Email officiel du SACCO (optionnel)',
    'sacco_location': 'Localisation du SACCO',
    'select_province': '-- Sélectionnez une province --',
    'province': 'Province',
    'territory_commune': 'Territoire / Commune',
    'city_village_quartier': 'Ville / Village / Quartier',
    'detailed_physical_address_optional': 'Adresse physique détaillée (facultatif)',
    'applicant_initiator_info': "Informations du demandeur / Initiateur",
    'applicant_full_name': 'Nom complet du demandeur',
    'applicant_role': 'Fonction / Rôle dans le SACCO',
    'applicant_contact_email_optional': 'Email de contact du demandeur (optionnel)',
    'applicant_contact_phone': 'Téléphone de contact du demandeur',
    'reason_description': 'Motif / Description',
    'describe_sacco_project': "Décrivez brièvement votre projet de SACCO...",
    'system_settings': 'Paramètres système',
    'primary_currency': 'Devise primaire',
    'secondary_currency': 'Devise secondaire',
    'status': 'Statut',
    'save_sacco': 'Enregistrer le SACCO',

    # edit.blade.php
    'edit_sacco': 'Modifier le SACCO :name',
    'update_sacco': 'Mettre à jour le SACCO',

    # show.blade.php
    'view_sacco': 'Voir le SACCO :name',
    'registration_number': "Numéro d'enregistrement",
    'n_a': 'N/A',
    'physical_address': 'Adresse physique',
    'official_mobile_money': 'Numéro Mobile Money officiel',
    'primary_currency': 'Devise primaire',
    'secondary_currency': 'Devise secondaire',
    'creation_date': 'Date de création',
    'official_account': 'Compte officiel',
    'official_email_auto_generated_approval': 'Email officiel généré automatiquement lors de l\'approbation',
    'no_official_account_found': 'Aucun compte officiel trouvé',
}

new_en = {
    # index.blade.php
    'sacos_list': 'SACCOs List',
    'new_sacco': 'New SACCO',
    'search_and_filter': 'Search and Filter',
    'search_by_name_code_registration': 'Search by name, code or registration number',
    'all_statuses': 'All Statuses',
    'search': 'Search',
    'reset': 'Reset',
    'no_sacos_found': 'No SACCOs found',
    'name': 'Name',
    'code': 'Code',
    'official_email': 'Official Email',
    'none': 'None',
    'official_email_auto_generated': 'Official email auto-generated',
    'actions': 'Actions',
    'view': 'View',
    'edit': 'Edit',
    'delete': 'Delete',
    'confirm_delete_sacco': 'Are you sure you want to delete this SACCO? This action is irreversible.',
    'manage_official_account': 'Manage Official Account',

    # create.blade.php
    'sacco_information': 'SACCO Information',
    'please_correct_errors': 'Please correct the following errors',
    'sacco_name': 'SACCO Name',
    'official_mobile_money': 'Official SACCO Mobile Money Number',
    'where_member_money_deposited': 'Number where member money will be deposited',
    'official_email_optional': 'Official SACCO Email (optional)',
    'sacco_location': 'SACCO Location',
    'select_province': '-- Select a province --',
    'province': 'Province',
    'territory_commune': 'Territory / Commune',
    'city_village_quartier': 'City / Village / Quarter',
    'detailed_physical_address_optional': 'Detailed physical address (optional)',
    'applicant_initiator_info': 'Applicant / Initiator Information',
    'applicant_full_name': 'Applicant Full Name',
    'applicant_role': 'Function / Role in SACCO',
    'applicant_contact_email_optional': 'Applicant Contact Email (optional)',
    'applicant_contact_phone': 'Applicant Contact Phone',
    'reason_description': 'Reason / Description',
    'describe_sacco_project': 'Briefly describe your SACCO project...',
    'system_settings': 'System Settings',
    'primary_currency': 'Primary Currency',
    'secondary_currency': 'Secondary Currency',
    'status': 'Status',
    'save_sacco': 'Save SACCO',

    # edit.blade.php
    'edit_sacco': 'Edit SACCO: :name',
    'update_sacco': 'Update SACCO',

    # show.blade.php
    'view_sacco': 'View SACCO: :name',
    'registration_number': 'Registration Number',
    'n_a': 'N/A',
    'physical_address': 'Physical Address',
    'official_mobile_money': 'Official Mobile Money',
    'primary_currency': 'Primary Currency',
    'secondary_currency': 'Secondary Currency',
    'creation_date': 'Creation Date',
    'official_account': 'Official Account',
    'official_email_auto_generated_approval': 'Official email auto-generated upon approval',
    'no_official_account_found': 'No official account found',
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