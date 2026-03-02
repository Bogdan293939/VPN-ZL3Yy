<?php
// =============================================
// VPN Subscription Auto-Expire Script
// =============================================

// Отключаем вывод ошибок в браузер
ini_set('display_errors', 0);
error_reporting(0);

// Устанавливаем правильные заголовки
header('Content-Type: text/plain; charset=utf-8');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// =============================================
// НАСТРОЙКИ
// =============================================

// Время истечения подписки (Unix timestamp)
// 1761744000 = 29 октября 2025 года
$expire_time = 1761744000;

// Текущее время
$current_time = time();

// Мета-данные подписки
$profile_title = "ZL3YY VPN⚡";
$profile_update_interval = "2";
$support_url = "https://t.me/News_ZL3YY";
$announce = "Самый лутший VPN в России для обхода белых списков ⚡";

// =============================================
// ВСЕ ДОСТУПНЫЕ СЕРВЕРЫ (активные)
// =============================================

$active_servers = [
    "vless://a41da912-1ad3-4897-880c-fa6228255288@ni.yurichdelaet.ru:443?encryption=none&security=reality&flow=xtls-rprx-vision&fp=chrome&pbk=pwrZYfLntgE9L7OGL53DGpLFRXcXyzoMjJcND9c5fys&sni=ni.yurichdelaet.ru&sid=a117ee845bcb2246&type=tcp&headerType=none#%F0%9F%87%B3%F0%9F%87%B1%20%D0%9D%D0%B8%D0%B4%D0%B5%D1%80%D0%BB%D0%B0%D0%BD%D0%B4%D1%8B",
    "vless://7f7372ef-37db-47ef-a980-e1642daaab7a@87.121.162.181:54443?security=tls&encryption=none&alpn=http/1.1&fp=chrome&type=tcp&headerType=none&sni=max.ru&allowInsecure=1#🇦🇱 Албания",
    "vless://7f7372ef-37db-47ef-a980-e1642daaab7a@193.42.11.94:54443?security=tls&encryption=none&alpn=http/1.1&fp=chrome&type=tcp&headerType=none&sni=yandex.ru&allowInsecure=1#🇩🇪 Германия",
    "vless://437016d2-56fb-40ff-9220-e60d54fd5b57@pl.datanode-internal.net:443?security=reality&encryption=none&pbk=r6lN34m1nN-xQZ458j5NPD5xJ3_QBF2bGzY4KJEo4ic&headerType=none&fp=qq&type=tcp&flow=xtls-rprx-vision&sni=sun9-35.userapi.com&sid=abbcd128#🇵🇱 Польша",
    "vless://437016d2-56fb-40ff-9220-e60d54fd5b57@hole.datanode-internal.net:51760?security=reality&encryption=none&pbk=r6lN34m1nN-xQZ458j5NPD5xJ3_QBF2bGzY4KJEo4ic&headerType=none&fp=qq&type=tcp&flow=xtls-rprx-vision&sni=eh.vk.com&sid=abbcd128#🇷🇺 Мобильная связь #1",
    "vless://437016d2-56fb-40ff-9220-e60d54fd5b57@hole.datanode-internal.net:9443?security=reality&encryption=none&pbk=r6lN34m1nN-xQZ458j5NPD5xJ3_QBF2bGzY4KJEo4ic&headerType=none&fp=qq&type=tcp&flow=xtls-rprx-vision&sni=eh.vk.com&sid=abbcd128#🇩🇪 Мобильная связь #2",
    "vless://437016d2-56fb-40ff-9220-e60d54fd5b57@hole.datanode-internal.net:8443?security=reality&encryption=none&pbk=r6lN34m1nN-xQZ458j5NPD5xJ3_QBF2bGzY4KJEo4ic&headerType=none&fp=qq&type=tcp&flow=xtls-rprx-vision&sni=eh.vk.com&sid=abbcd128#🇵🇱 Мобильная связь #3",
    "vless://7f7372ef-37db-47ef-a980-e1642daaab7a@51.250.31.236:443?security=tls&encryption=none&alpn=http/1.1&fp=chrome&type=ws&path=%2F&host=finland.hsshsjsjsospsppsa.online&sni=finland.hsshsjsjsospsppsa.online&allowInsecure=1#🇷🇺 Россия LTE",
    "vless://7583a036-68c8-43a9-8bf6-4d27c984adf6@37.139.32.78:443?security=reality&encryption=none&pbk=10rVZPoOUP1TlQviIAsQ_jAROX0fRQxH0C92nq_zGQc&headerType=none&fp=edge&type=tcp&flow=xtls-rprx-vision&sni=ads.x5.ru&sid=43dcff53849b81e6#🇩🇪 LTE - Обход глушилок #1",
    "vless://7583a036-68c8-43a9-8bf6-4d27c984adf6@37.139.32.94:443?security=reality&encryption=none&pbk=10rVZPoOUP1TlQviIAsQ_jAROX0fRQxH0C92nq_zGQc&headerType=none&fp=edge&type=tcp&flow=xtls-rprx-vision&sni=api-maps.yandex.ru&sid=43dcff53849b81e6#🇩🇪 LTE - Обход глушилок #2",
    "vless://7583a036-68c8-43a9-8bf6-4d27c984adf6@37.139.32.66:443?security=reality&encryption=none&pbk=10rVZPoOUP1TlQviIAsQ_jAROX0fRQxH0C92nq_zGQc&headerType=none&fp=edge&type=tcp&flow=xtls-rprx-vision&sni=eh.vk.com&sid=43dcff53849b81e6#🇩🇪 LTE - Обход глушилок #3"
];

// =============================================
// СЕРВЕР ПОСЛЕ ИСТЕЧЕНИЯ
// =============================================

$expired_server = "vless://a41da912-1ad3-4897-880c-fa6228255288@ni.yurichdelaet.ru:443?encryption=none&security=reality&flow=xtls-rprx-vision&fp=chrome&pbk=pwrZYfLntgE9L7OGL53DGpLFRXcXyzoMjJcND9c5fys&sni=ni.yurichdelaet.ru&sid=a117ee845bcb2246&type=tcp&headerType=none#‼️%20Подписка%20истекла%20" . date('d.m.Y', $expire_time) . "%20‼️";

// =============================================
// ФОРМИРОВАНИЕ ВЫВОДА
// =============================================

// Начинаем вывод с мета-информации
echo "#profile-title: " . $profile_title . "\n";
echo "#profile-update-interval: " . $profile_update_interval . "\n";
echo "#support-url: " . $support_url . "\n";

// Проверяем статус подписки
if ($current_time > $expire_time) {
    // Подписка истекла
    echo "#announce: ⚠️ ПОДПИСКА ИСТЕКЛА " . date('d.m.Y', $expire_time) . " ⚠️\n";
    echo "#subscription-userinfo: upload=0; download=0; total=0; expire=" . $expire_time . "\n\n";
    
    // Отдаем только один сервер с предупреждением
    echo $expired_server . "\n";
} else {
    // Подписка активна
    $days_left = floor(($expire_time - $current_time) / 86400);
    echo "#announce: " . $announce . " (осталось дней: " . $days_left . ")\n";
    echo "#subscription-userinfo: upload=0; download=0; total=0; expire=" . $expire_time . "\n\n";
    
    // Отдаем все активные серверы
    foreach ($active_servers as $server) {
        echo $server . "\n";
    }
}

// Добавляем пустую строку в конце
echo "\n";
?>
