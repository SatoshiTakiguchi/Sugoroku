# ゲームの流れ
1. 行動の選択
   - サイコロを振る
   - アイテムを使う
2. マスに止まる
   - イベントの発生  
    イベント一覧(04/22現在)
     - 単純移動
     - アイテム獲得
3. 行動後の確認
   - ゴールまで残り何マスか


## 各クラス説明

### Player.php
- 引数でオート操作（サイコロのみ）の設定


### Board.csv
- ボードのランダム作成
- マスの種類(ボードへの記述方法)  
  止まったらそれぞれに応じた効果がある。
  - 何もなし
  - マスすすむ
  - マスもどる
  - アイテム

### WaitProsessing.php
- 待機処理

### Dice.php
- 出目の範囲変更可能

### Ivent.php 
- イベント一覧(04/22現在)
  - 単純移動
  - アイテム獲得

### Item.php
サイコロの代わりにイベントを起こす