# SSH connexion

```bash
ssh-keygen
# passphrase vide (uniquement entre les machines de l'enseirb)
cd ./.ssh
# Copier le id.pub sous authorized_keys
cat .id_rsa.pub >> authorized_keys
```

```bash
cat ~/.ssh/id_rsa.pub | ssh ltarascon001@ssh.enseirb-matmeca.fr "mkdir -p ~/.ssh && cat >>  ~/.ssh/authorized_keys"
```
