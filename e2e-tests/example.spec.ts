import { test, expect } from '@playwright/test';

test.use({
  ignoreHTTPSErrors: true
});

test('test', async ({ page }) => {
  await page.goto('https://localhost:4430/');
  await page.getByRole('link', { name: '新入荷' }).click();
  await page.getByRole('listitem').filter({ hasText: 'チェリーアイスサンド ￥3,080 数量 カートに入れる' }).getByRole('button', { name: 'カートに入れる' }).click();
  // await page.locator('a >> text=カートへ進む').click();
  // await page.getByRole('link', { name: 'カートへ進む' }).click();
  await page.getByRole('link', { name: 'レジに進む' }).click();
  await page.getByRole('link', { name: 'ゲスト購入' }).click();
  await page.getByPlaceholder('姓').click();
  await page.getByPlaceholder('姓').fill('石');
  await page.getByPlaceholder('姓').press('Tab');
  await page.locator('#nonmember_name_name02').fill('九部');
  await page.locator('#nonmember_name_name02').press('Tab');
  await page.getByPlaceholder('セイ').fill('イシ');
  await page.getByPlaceholder('セイ').press('Tab');
  await page.getByPlaceholder('メイ').fill('キュウブ');
  await page.getByPlaceholder('メイ').press('Tab');
  await page.getByLabel('会社名').press('Tab');
  await page.getByPlaceholder('例：5300001').fill('5430036');
  await page.selectOption('#nonmember_address_pref', '大阪府');
  await page.getByPlaceholder('市区町村名(例：大阪市北区)').fill('大阪市天王寺区小宮町');
  await page.getByPlaceholder('市区町村名(例：大阪市北区)').press('Tab');
  await page.getByPlaceholder('番地・ビル名(例：西梅田1丁目6-8)').fill('11');
  await page.getByPlaceholder('番地・ビル名(例：西梅田1丁目6-8)').press('Tab');
  await page.getByPlaceholder('例：11122223333').fill('09011112222');
  await page.getByPlaceholder('例：11122223333').press('Tab');
  await page.getByPlaceholder('例：ec-cube@example.com').fill('user@example.com');
  await page.getByPlaceholder('例：ec-cube@example.com').press('Tab');
  await page.getByPlaceholder('確認のためもう一度入力してください').fill('user@example.com');
  await page.getByRole('button', { name: '次へ' }).click();
  await page.getByText('クレジットカード').click();
  await page.locator('iframe.sq-card-component').waitFor();
  await page.frameLocator('iframe.sq-card-component').locator('#cardNumber').fill('4111111111111111');
  await page.frameLocator('iframe.sq-card-component').getByPlaceholder('MM/YY').fill('11/25');
  await page.frameLocator('iframe.sq-card-component').getByPlaceholder('CVV').fill('333');
  await page.frameLocator('iframe.sq-card-component').getByPlaceholder('ZIP').fill('444444');
  await page.frameLocator('iframe.sq-card-component').getByPlaceholder('ZIP').press('Tab');
  await page.getByRole('button', { name: '確認する' }).click();
  await page.getByRole('button', { name: '注文する' }).click();
  await page.getByRole('heading', { name: 'ご注文ありがとうございました' }).click();
  await page.getByRole('link', { name: 'トップページへ' }).click();
});
