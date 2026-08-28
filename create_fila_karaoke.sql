-- Script para criar a tabela de fila de karaokê no PostgreSQL

CREATE TABLE IF NOT EXISTS fila_karaoke (
    id SERIAL PRIMARY KEY,
    codigo VARCHAR(10) NOT NULL,
    ordem INTEGER NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'pendente',
    prioridade INTEGER NOT NULL DEFAULT 0,
    adicionado_por VARCHAR(100),
    favorito BOOLEAN NOT NULL DEFAULT FALSE,
    data_adicionado TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT now(),
    atualizado_em TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT now(),
    CONSTRAINT fk_musica_codigo FOREIGN KEY (codigo)
        REFERENCES musicas_karaoke(codigo)
        ON DELETE CASCADE
);

CREATE INDEX IF NOT EXISTS idx_fila_status_ordem ON fila_karaoke (status, ordem);
CREATE INDEX IF NOT EXISTS idx_fila_codigo ON fila_karaoke (codigo);
