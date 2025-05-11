const express = require('express');
const cors = require('cors');
const app = express();
app.use(cors());
app.use(express.json());

// YÖNETİCİ ŞİFRESİ ARTIK SADECE BURADA!
const YONETICI_SIFRESI = 'AdminEmo123';

app.post('/api/check-manager-password', (req, res) => {
  const { password } = req.body;
  if (password === YONETICI_SIFRESI) {
    res.json({ valid: true });
  } else {
    res.json({ valid: false });
  }c
});

const PORT = 3001;
app.listen(PORT, () => console.log(`Yönetici API çalışıyor: http://localhost:${PORT}`));
