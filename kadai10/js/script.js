// Google Mapsの初期化
function initMap() {
  const map = new google.maps.Map(document.getElementById("map"), {
    center: { lat: 0, lng: 0 },
    zoom: 2,
  });

  // マップをクリックしたときのイベントリスナーを追加
  map.addListener("click", (event) => {
    // クリックした位置の緯度経度を取得
    const latitude = event.latLng.lat();
    const longitude = event.latLng.lng();

    // 緯度経度から国と都市を取得
    const geocoder = new google.maps.Geocoder();
    geocoder.geocode({ location: { lat: latitude, lng: longitude } }, (results, status) => {
      if (status === "OK") {
        if (results[0]) {
          // 取得した情報から国と都市を抽出
          let country = "";
          let city = "";
          for (let i = 0; i < results[0].address_components.length; i++) {
            const addressType = results[0].address_components[i].types[0];
            if (addressType === "country") {
              country = results[0].address_components[i].long_name;
            } else if (addressType === "locality" || addressType === "administrative_area_level_1") {
              city = results[0].address_components[i].long_name;
            }
          }

          // 取得した情報を位置フィールドに自動入力
          document.getElementById("location").value = country + ", " + city;

          // 緯度経度を隠しフィールドに設定
          document.getElementById("latitude").value = latitude;
          document.getElementById("longitude").value = longitude;

          // デバッグ用にログを出力
          console.log(latitude, longitude);
        } else {
          window.alert("位置情報が見つかりませんでした。");
        }
      } else {
        window.alert("位置情報の取得中にエラーが発生しました。");
      }
    });
  });
}
      // // date入力確認

      // let input_submit = document.querySelector("input[type=submit]");
      // input_submit.addEventListener("click", function(e){

      //   // フォームの送信キャンセル
      //   e.preventDefault();

      //   // date
      //   let date = document.querySelector("input[name=date]");
      //   console.log(date.value); // テスト太郎

      //    // latitude
      //           let latitude = document.querySelector("input[name=latitude]");
      //   console.log(latitude.value); // テスト太郎

      //            // latitude
      //            let longitude = document.querySelector("input[name=longitude]");
      //   console.log(longitude.value); // テスト太郎
      // });