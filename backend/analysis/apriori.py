import pymysql
import pandas as pd

connection = pymysql.connect(
    host='localhost',
    user='root',
    password='',
    database='cafe_db'
)

cursor = connection.cursor()

query = """ SELECT oi.order_id, m.name FROM order_items oi JOIN menu m ON oi.product_id = m.id """
cursor.execute(query)
rows = cursor.fetchall()
# print(rows)

transactions = {}

for order_id, product in rows:
    if order_id not in transactions:
        transactions[order_id] = []
    transactions[order_id].append(product)

# print(transactions)

basket = list(transactions.values())
# print(basket)

from mlxtend.preprocessing import TransactionEncoder

encoder = TransactionEncoder()
encoded = encoder.fit(basket).transform(basket)

df = pd.DataFrame(encoded, columns = encoder.columns_)
# print(df)

from mlxtend.frequent_patterns import apriori

frequent = apriori(df,  min_support=0.2, use_colnames=True)
# print(frequent)

from mlxtend.frequent_patterns import association_rules

rules = association_rules(frequent, metric='confidence', min_threshold=0.5)
rules = rules.sort_values(by="confidence", ascending=False)
# print(rules[['antecedents', 'consequents', 'support', 'confidence', 'lift']])

import json

rules_json = []
for _, row in rules.iterrows():
    rules_json.append({
        "if": list(row["antecedents"]),
        "then": list(row["consequents"]),
        "confidence": float(row["confidence"]),
        "support": float(row["support"]),
        "lift": float(row["lift"])
    })

with open("recommendations.json", "w", encoding="utf-8") as f:
    json.dump(rules_json, f, ensure_ascii=False, indent=4)

print("Правила збережено")