# Room
Room is a websocket application that prioritizes privacy. We have developed a unique feature called "Hide Room" on each server, ensuring that communication remains end-to-end encrypted. Our concept revolves around the idea of impermanence, where each Room is created with a temporary lifespan. This approach represents a significant improvement over the conventional notion of "Total burn," ensuring enhanced security and privacy for our users.

## Outer Libs:
Ratchet     - Websockets
JSencrypt   - RSA client to client 
Forge       - RSA php to client server login (session)
Php openssl - RSA server

## Own libraries: Maybe in future:
ModuloMath
PrimaryTestLib
AES 
RSA 
Ws (websocket)

### Functionality
+ 1. Hub comunication for standard chatRoom407Alfa.
+ 2. Encrypt login parameters.
- 3. Search client by five letters.
- 4.  

##### Communication standard chatRoom407Alfa 

--search
request
    <tb>

###### Done:
xx.yy.2024 - Build prototype Communication standard chatRoom407Alfa
xx.yy.2024 - Add enrcryption by libs
xx.yy.2024 - Add secure login deliveryKey.php (secure against sniffing 'man in the middle')

####### Open:
1. Do session login cleaner. (Theard to clean not used session key to keep login)
2. Make documentation about stanadrd comunnication chatRoom407Alfa.
3. Make Validate date.

