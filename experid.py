import re
import time
from datetime import datetime

# Настройки
EXPIRE_KEY = "vless://a41da912-1ad3-4897-880c-fa6228255288@ni.yurichdelaet.ru:443?encryption=none&security=reality&flow=xtls-rprx-vision&fp=chrome&pbk=pwrZYfLntgE9L7OGL53DGpLFRXcXyzoMjJcND9c5fys&sni=ni.yurichdelaet.ru&sid=a117ee845bcb2246&type=tcp&headerType=none#‼️%20Подписка%20истекла%20‼️"

# Читаем VPN.txt
with open('VPN.txt', 'r', encoding='utf-8') as f:
    content = f.read()

# Ищем expire
expire = None
for line in content.split('\n'):
    if 'expire=' in line:
        match = re.search(r'expire=(\d+)', line)
        if match:
            expire = int(match.group(1))
            break

# Проверяем
if expire and int(time.time()) > expire:
    print(f"❌ ПОДПИСКА ИСТЕКЛА: {datetime.fromtimestamp(expire)}")
    
    # Сохраняем только заголовки + один ключ
    new_lines = []
    for line in content.split('\n'):
        if line.startswith('#'):
            new_lines.append(line)
        else:
            break
    new_lines.append(EXPIRE_KEY)
    
    with open('VPN.txt', 'w', encoding='utf-8') as f:
        f.write('\n'.join(new_lines))
    
    print("✅ VPN.txt обновлен (только сообщение об истечении)")
else:
    if expire:
        print(f"✅ Подписка активна до: {datetime.fromtimestamp(expire)}")
    else:
        print("✅ Подписка активна")
