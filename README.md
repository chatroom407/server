# Room
Room is a websocket application that prioritizes privacy. We have developed a unique feature called "Hide Room" on each server, ensuring that communication remains end-to-end encrypted. Our concept revolves around the idea of impermanence, where each Room is created with a temporary lifespan. This approach represents a significant improvement over the conventional notion of "Total burn," ensuring enhanced security and privacy for our users.

## Outer Libraries:
- **Ratchet**: Websockets
- **JSencrypt**: RSA client-to-client encryption
- **Forge**: RSA PHP to client-server login (session)
- **PHP OpenSSL**: RSA server-side encryption

## Own Libraries (Maybe in future):
- ModuloMath
- PrimaryTestLib
- AES
- RSA
- Ws (websocket)

### Functionality
1. **Hub communication** for standard `chatRoom407Alfa`.
2. **Encrypt login parameters** for secure authentication.
3. **Search client by five letters**.
4. (Feature to be defined)

##### Communication Standard for `chatRoom407Alfa`

**--search request:**  
`<tb>` ...

###### Done:
- xx.yy.2024: Built prototype for communication standard `chatRoom407Alfa`.
- xx.yy.2024: Added encryption via external libraries.
- xx.yy.2024: Secured login with `deliveryKey.php` (protection against man-in-the-middle attacks).

####### Open:
1. Implement session login cleaner (thread to clean unused session keys for secure login).
2. Create documentation for the communication standard `chatRoom407Alfa`.
3. Make implementation date validation.
