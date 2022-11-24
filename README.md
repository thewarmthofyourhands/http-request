Native Request
================

The library provides interaction over the HTTP protocol

The basis is a model of Http packets.
However, it does not support psr and should not be used at all.

The library consists of:
1. HTTP message builders for both request and response
2. Parsers that allow you to quickly pull out of HTTP messages
   data in a format that is easy to process
3. RequestSender - implementation of sending and receiving messages (low-level sending via socket is used)
4. A simple Client and a wrapper either for simple native requests or for creating full-fledged Request services
(Examples are in the example folder)

Native Request (RUS)
================
Библиотека обеспечивает взаимодействие по HTTP протоколу

Основа представляет из себя модели Http пакетов.
Однако не поддерживает psr и ему не следует вовсе.

Библиотека состоит из:
1. Билдеры HTTP сообщений, как для request, так и для response
2. Парсеры, которые позволяют оперативно вытащить из HTTP сообщений
   данные в удобном для обработки формате
3. RequestSender - реализация отправки и приемки сообщений (используется низкоуровневая отправка через сокет)
4. Простенький Клиент и обертка либо для простых нейтивных запросов, либо для создания полноценных Request сервисов (Примеры лежат в папке example)
