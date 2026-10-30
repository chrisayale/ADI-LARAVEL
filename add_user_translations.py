import json

# Read existing files
with open('lang/fr.json', 'r', encoding='utf-8') as f:
    fr = json.load(f)

with open('lang/en.json', 'r', encoding='utf-8') as f:
    en = json.load(f)

# Add new translation keys
new_fr = {
    'manage_members': 'Gérer les membres',
    'add_new_user': 'Ajouter un utilisateur',
    'filter_members': 'Filtrer les membres',
    'search': 'Rechercher',
    'search_placeholder': 'Nom, Email, N° adhésion...',
    'all_status': 'Tous les statuts',
    'all_roles': 'Tous les rôles',
    'filter': 'Filtrer',
    'members_list': 'Liste des membres',
    'member': 'Membre',
    'contact': 'Contact',
    'joined_date': 'Date d\'adhésion',
    'actions': 'Actions',
    'n_a': 'N/A',
    'view': 'Voir',
    'edit': 'Modifier',
    'activate': 'Activer',
    'deactivate': 'Désactiver',
    'delete': 'Supprimer',
    'confirm_delete': 'Êtes-vous sûr de vouloir supprimer ce membre ?',
    'no_members_found': 'Aucun membre trouvé',
}

new_en = {
    'manage_members': 'Manage Members',
    'add_new_user': 'Add New User',
    'filter_members': 'Filter Members',
    'search': 'Search',
    'search_placeholder': 'Name, Email, Membership No...',
    'all_status': 'All Status',
    'all_roles': 'All Roles',
    'filter': 'Filter',
    'members_list': 'Members List',
    'member': 'Member',
    'contact': 'Contact',
    'joined_date': 'Joined Date',
    'actions': 'Actions',
    'n_a': 'N/A',
    'view': 'View',
    'edit': 'Edit',
    'activate': 'Activate',
    'deactivate': 'Deactivate',
    'delete': 'Delete',
    'confirm_delete': 'Are you sure you want to delete this member?',
    'no_members_found': 'No members found',
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