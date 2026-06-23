import openpyxl, json

wb = openpyxl.load_workbook('demo/Dealer_price-list.xlsx')
ws = wb.active

categories = {}
products = []
current_cat = None
cat_count = 0

for row in ws.iter_rows(min_row=2, values_only=True):
    name = str(row[0]).strip() if row[0] else ''
    sku = str(row[1]).strip() if row[1] else ''
    price = 0
    try:
        price = float(row[3]) if row[3] else 0
    except (ValueError, TypeError):
        pass

    if not name or name == 'None': continue

    is_cat = (not sku or sku == 'None' or price == 0)

    if cat_count < 20 and is_cat:
        current_cat = name
        if name not in categories:
            categories[name] = []
            cat_count += 1
        continue

    if current_cat and price > 0 and len(products) < 100:
        categories[current_cat].append(name)
        products.append({
            'category': current_cat,
            'name': name[:255],
            'sku': sku if sku != 'None' else '',
            'price': price
        })

while cat_count < 20:
    cat_name = 'Category ' + str(cat_count + 1)
    categories[cat_name] = []
    cat_count += 1

result = {'categories': list(categories.keys())[:20], 'products': products[:100]}

with open('demo/demo_data.json', 'w', encoding='utf-8') as f:
    json.dump(result, f, ensure_ascii=False, indent=2)

print('Categories:', len(result['categories']))
print('Products:', len(result['products']))
