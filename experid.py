#!/usr/bin/env python3
# -*- coding: utf-8 -*-

"""
Скрипт для обработки подписки Happ proxy (ZL3YY VPN)
При истечении срока подписки оставляет только один служебный ключ
GitHub: https://github.com/yourusername/happ-proxy-expire-handler
"""

import re
import time
from datetime import datetime
import urllib.request
import urllib.error
import os

# Конфигурация
# Используем вашу актуальную ссылку на подписку
SUBSCRIPTION_URL = "https://raw.githubusercontent.com/Bogdan293939/VPN-ZL3Yy/refs/heads/main/VPN.txt"
EXPIRE_MESSAGE_KEY = "vless://a41da912-1ad3-4897-880c-fa6228255288@ni.yurichdelaet.ru:443?encryption=none&security=reality&flow=xtls-rprx-vision&fp=chrome&pbk=pwrZYfLntgE9L7OGL53DGpLFRXcXyzoMjJcND9c5fys&sni=ni.yurichdelaet.ru&sid=a117ee845bcb2246&type=tcp&headerType=none#‼️%20Подписка%20истекла%20‼️"

def get_subscription_content(url):
    """Получает содержимое подписки по URL"""
    try:
        headers = {'User-Agent': 'Happ/1.0'}
        req = urllib.request.Request(url, headers=headers)
        with urllib.request.urlopen(req, timeout=10) as response:
            return response.read().decode('utf-8')
    except Exception as e:
        print(f"Ошибка при получении подписки: {e}")
        return None

def parse_subscription_headers(content):
    """Парсит заголовки подписки"""
    headers = {}
    lines = content.split('\n')
    
    for line in lines:
        if line.startswith('#'):
            if ':' in line:
                key, value = line[1:].split(':', 1)
                headers[key.strip()] = value.strip()
        else:
            break
    
    return headers

def extract_expire_time(content):
    """Извлекает время истечения подписки из заголовков"""
    headers = parse_subscription_headers(content)
    expire_str = headers.get('subscription-userinfo', '')
    
    # Ищем параметр expire
    match = re.search(r'expire=(\d+)', expire_str)
    if match:
        return int(match.group(1))
    return None

def is_subscription_expired(expire_timestamp):
    """Проверяет, истекла ли подписка"""
    if not expire_timestamp:
        return False
    
    current_time = int(time.time())
    return current_time > expire_timestamp

def process_subscription(content):
    """Обрабатывает подписку"""
    if not content:
        return None
    
    expire_time = extract_expire_time(content)
    
    if expire_time and is_subscription_expired(expire_time):
        # Подписка истекла - возвращаем только сообщение
        expire_date = datetime.fromtimestamp(expire_time).strftime('%Y-%m-%d %H:%M:%S')
        print(f"⚠️  Подписка истекла {expire_date}")
        
        # Формируем новый контент с заголовками и служебным ключом
        new_content = []
        lines = content.split('\n')
        
        # Сохраняем заголовки (все строки, начинающиеся с #)
        for line in lines:
            if line.startswith('#'):
                new_content.append(line)
            else:
                break
        
        # Добавляем служебный ключ
        new_content.append(EXPIRE_MESSAGE_KEY)
        
        return '\n'.join(new_content)
    else:
        # Подписка активна - возвращаем как есть
        if expire_time:
            expire_date = datetime.fromtimestamp(expire_time).strftime('%Y-%m-%d %H:%M:%S')
            print(f"✅ Подписка активна до {expire_date}")
        else:
            print("❓ Не удалось определить срок действия подписки")
        return content

def save_output(content, output_file='output.txt'):
    """Сохраняет результат в файл"""
    with open(output_file, 'w', encoding='utf-8') as f:
        f.write(content)
    print(f"💾 Результат сохранен в {output_file}")
    return output_file

def main():
    """Основная функция"""
    print("=" * 60)
    print("🔌 Happ proxy - Обработчик истечения подписки ZL3YY VPN")
    print("=" * 60)
    
    print(f"📡 URL подписки: {SUBSCRIPTION_URL}")
    
    # Получаем подписку
    content = get_subscription_content(SUBSCRIPTION_URL)
    if not content:
        print("❌ Не удалось получить подписку")
        return
    
    print(f"📦 Размер данных: {len(content)} байт")
    print(f"🔑 Количество ключей: {content.count('vless://')}")
    
    # Обрабатываем
    result = process_subscription(content)
    if result:
        output_file = save_output(result)
        
        # Показываем первые несколько строк результата
        print("\n📄 Первые 5 строк результата:")
        result_lines = result.split('\n')[:5]
        for line in result_lines:
            if line.startswith('#'):
                print(f"  {line}")
            else:
                # Сокращаем длинные ключи для отображения
                short_line = line[:60] + "..." if len(line) > 60 else line
                print(f"  {short_line}")
        
        print(f"\n✨ Готово! Файл '{output_file}' создан.")
        print(f"🔗 Ссылка для Happ proxy: https://raw.githubusercontent.com/ВАШ_АККАУНТ/happ-proxy-expire-handler/main/{output_file}")
    else:
        print("❌ Ошибка обработки")

if __name__ == "__main__":
    main()
