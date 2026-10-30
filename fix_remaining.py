import json

with open('lang/fr.json', 'r', encoding='utf-8') as f:
    fr = json.load(f)

# Fix remaining mojibake entries
fr['monthly_savings'] = 'Épargne mensuelle'
fr['my_savings'] = 'Mon Épargne'
fr['installment'] = 'Échéance'
fr['team'] = 'Équipe'

with open('lang/fr.json', 'w', encoding='utf-8') as f:
    json.dump(fr, f, indent=4, ensure_ascii=False)

print('Fixed remaining mojibake entries')
print('monthly_savings:', fr['monthly_savings'])
print('my_savings:', fr['my_savings'])
print('installment:', fr['installment'])
print('team:', fr['team'])