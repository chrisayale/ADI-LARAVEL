import json

# Read existing files
with open('lang/fr.json', 'r', encoding='utf-8') as f:
    fr = json.load(f)

with open('lang/en.json', 'r', encoding='utf-8') as f:
    en = json.load(f)

# Add new translation keys for user creation/edit views
new_fr = {
    # Page titles
    'edit_user': 'Modifier l\'utilisateur',
    'add_new_user': 'Ajouter un utilisateur',
    'edit_user_information': 'Modifier les informations de l\'utilisateur',
    'user_information': 'Informations utilisateur',

    # Navigation
    'view_details': 'Voir les détails',
    'back_to_list': 'Retour à la liste',

    # Messages
    'validation_errors': 'Erreurs de validation',
    'unsaved_changes_warning': 'Vous avez des modifications non enregistrées. Voulez-vous vraiment quitter ?',
    'file_size_limit': 'La taille du fichier ne doit pas dépasser 2 Mo.',
    'valid_image_types': 'Veuillez sélectionner une image valide (JPEG, PNG, GIF).',

    # Sections
    'personal_information': 'Informations personnelles',
    'next_of_kin_information': 'Informations du proche parent',
    'membership_information': 'Informations d\'adhésion',
    'employment_information': 'Informations d\'emploi',
    'account_information': 'Informations du compte',
    'change_password_optional': 'Changer le mot de passe (optionnel)',
    'danger_zone': 'Zone de danger',
    'delete_user_account': 'Supprimer le compte utilisateur',
    'delete_user_warning': 'Cette action est irréversible et supprimera définitivement le compte utilisateur.',
    'confirm_delete_user': 'Êtes-vous sûr de vouloir supprimer cet utilisateur ?',
    'yes_delete_user': 'Oui, supprimer l\'utilisateur',
    'action_cannot_undo': 'Cette action ne peut pas être annulée.',
    'warning': 'Attention',

    # Form labels
    'user_type': 'Type d\'utilisateur',
    'user_type_cannot_change': 'Le type d\'utilisateur ne peut pas être modifié après la création.',
    'full_name': 'Nom complet',
    'email_address': 'Adresse e-mail',
    'phone_number': 'Numéro de téléphone',
    'national_id_passport': 'Carte d\'identité / Passeport',
    'physical_address': 'Adresse physique',
    'next_of_kin_name': 'Nom du proche parent',
    'next_of_kin_phone': 'Téléphone du proche parent',
    'relationship': 'Relation',
    'membership_number': 'Numéro d\'adhésion',
    'membership_date': 'Date d\'adhésion',
    'employee_id': 'Numéro d\'employé',
    'employment_date': 'Date d\'embauche',
    'position_title': 'Titre du poste',
    'password': 'Mot de passe',
    'confirm_password': 'Confirmer le mot de passe',
    'new_password': 'Nouveau mot de passe',
    'confirm_new_password': 'Confirmer le nouveau mot de passe',
    'account_status': 'Statut du compte',
    'profile_photo': 'Photo de profil',

    # Placeholders
    'membership_number_placeholder': 'Généré automatiquement (ex: MEM-2024-001)',
    'membership_number_placeholder_js': 'Généré automatiquement (ex: MEM-2024-001)',
    'membership_number_help': 'Laissez vide pour une génération automatique',
    'employee_id_placeholder': 'Généré automatiquement (ex: EMP-2024-001)',
    'employee_id_placeholder_js': 'Généré automatiquement (ex: EMP-2024-001)',
    'employee_id_help': 'Laissez vide pour une génération automatique',
    'position_placeholder': 'Ex: Comptable, Directeur, etc.',
    'photo_formats': 'JPG, PNG, GIF (max 2MB)',
    'password_min_chars': 'Minimum 8 caractères',
    'password_optional_help': 'Laissez vide pour ne pas changer le mot de passe',

    # Status options
    'active': 'Actif',
    'inactive': 'Inactif',
    'pending': 'En attente',
    'suspended': 'Suspendu',

    # Buttons
    'reset': 'Réinitialiser',
    'create_user': 'Créer l\'utilisateur',
    'update_user': 'Mettre à jour l\'utilisateur',
    'cancel': 'Annuler',
    'delete_user': 'Supprimer l\'utilisateur',

    # Member / Treasurer role labels
    'member': 'Membre',
    'treasurer': 'Trésorier',
}

new_en = {
    # Page titles
    'edit_user': 'Edit User',
    'add_new_user': 'Add New User',
    'edit_user_information': 'Edit User Information',
    'user_information': 'User Information',

    # Navigation
    'view_details': 'View Details',
    'back_to_list': 'Back to List',

    # Messages
    'validation_errors': 'Validation Errors',
    'unsaved_changes_warning': 'You have unsaved changes. Are you sure you want to leave?',
    'file_size_limit': 'File size must not exceed 2 MB.',
    'valid_image_types': 'Please select a valid image (JPEG, PNG, GIF).',

    # Sections
    'personal_information': 'Personal Information',
    'next_of_kin_information': 'Next of Kin Information',
    'membership_information': 'Membership Information',
    'employment_information': 'Employment Information',
    'account_information': 'Account Information',
    'change_password_optional': 'Change Password (Optional)',
    'danger_zone': 'Danger Zone',
    'delete_user_account': 'Delete User Account',
    'delete_user_warning': 'This action is irreversible and will permanently delete the user account.',
    'confirm_delete_user': 'Are you sure you want to delete this user?',
    'yes_delete_user': 'Yes, Delete User',
    'action_cannot_undo': 'This action cannot be undone.',
    'warning': 'Warning',

    # Form labels
    'user_type': 'User Type',
    'user_type_cannot_change': 'User type cannot be changed after creation.',
    'full_name': 'Full Name',
    'email_address': 'Email Address',
    'phone_number': 'Phone Number',
    'national_id_passport': 'National ID / Passport',
    'physical_address': 'Physical Address',
    'next_of_kin_name': 'Next of Kin Name',
    'next_of_kin_phone': 'Next of Kin Phone',
    'relationship': 'Relationship',
    'membership_number': 'Membership Number',
    'membership_date': 'Membership Date',
    'employee_id': 'Employee ID',
    'employment_date': 'Employment Date',
    'position_title': 'Position Title',
    'password': 'Password',
    'confirm_password': 'Confirm Password',
    'new_password': 'New Password',
    'confirm_new_password': 'Confirm New Password',
    'account_status': 'Account Status',
    'profile_photo': 'Profile Photo',

    # Placeholders
    'membership_number_placeholder': 'Auto-generated (e.g., MEM-2024-001)',
    'membership_number_placeholder_js': 'Auto-generated (e.g., MEM-2024-001)',
    'membership_number_help': 'Leave blank for auto-generation',
    'employee_id_placeholder': 'Auto-generated (e.g., EMP-2024-001)',
    'employee_id_placeholder_js': 'Auto-generated (e.g., EMP-2024-001)',
    'employee_id_help': 'Leave blank for auto-generation',
    'position_placeholder': 'e.g., Accountant, Manager, etc.',
    'photo_formats': 'JPG, PNG, GIF (max 2MB)',
    'password_min_chars': 'Minimum 8 characters',
    'password_optional_help': 'Leave blank to keep current password',

    # Status options
    'active': 'Active',
    'inactive': 'Inactive',
    'pending': 'Pending',
    'suspended': 'Suspended',

    # Buttons
    'reset': 'Reset',
    'create_user': 'Create User',
    'update_user': 'Update User',
    'cancel': 'Cancel',
    'delete_user': 'Delete User',

    # Member / Treasurer role labels
    'member': 'Member',
    'treasurer': 'Treasurer',
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