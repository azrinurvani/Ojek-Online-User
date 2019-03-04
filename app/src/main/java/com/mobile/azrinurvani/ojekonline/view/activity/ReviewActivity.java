package com.mobile.azrinurvani.ojekonline.view.activity;

import android.content.Intent;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.view.View;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.ImageView;
import android.widget.RatingBar;
import android.widget.TextView;
import android.widget.Toast;

import com.mobile.azrinurvani.ojekonline.MainActivity;
import com.mobile.azrinurvani.ojekonline.R;
import com.mobile.azrinurvani.ojekonline.helper.HeroHelper;
import com.mobile.azrinurvani.ojekonline.helper.MyContants;
import com.mobile.azrinurvani.ojekonline.helper.SessionManager;
import com.mobile.azrinurvani.ojekonline.model.DataDetailDriver;
import com.mobile.azrinurvani.ojekonline.model.DataHistory;
import com.mobile.azrinurvani.ojekonline.model.DataLoginRegis;
import com.mobile.azrinurvani.ojekonline.model.ResponseDetailDriver;
import com.mobile.azrinurvani.ojekonline.model.ResponseHistory;
import com.mobile.azrinurvani.ojekonline.model.ResponseLoginRegister;
import com.mobile.azrinurvani.ojekonline.network.InitRetrofit;
import com.rengwuxian.materialedittext.MaterialEditText;

import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.OnClick;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class ReviewActivity extends AppCompatActivity {

    @BindView(R.id.txtReview)
    TextView txtReview;
    @BindView(R.id.ivReviewFoto)
    ImageView ivReviewFoto;
    @BindView(R.id.txtReviewUserNama)
    TextView txtReviewUserNama;
    @BindView(R.id.ratingReview)
    RatingBar ratingReview;
    @BindView(R.id.txtReview2)
    TextView txtReview2;
    @BindView(R.id.edtReviewComment)
    MaterialEditText edtReviewComment;
    @BindView(R.id.txtReview3)
    TextView txtReview3;
    @BindView(R.id.cboReview)
    CheckBox cboReview;
    @BindView(R.id.btnReview)
    Button btnReview;
    public String idBooking;
    public SessionManager manager;
    public String idDriver;
    public float nilaiRating;
    public DataDetailDriver dataDriver;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_review);
        ButterKnife.bind(this);

        manager = new SessionManager(this);

        idBooking = getIntent().getStringExtra(MyContants.IDBOOKING);
//        driverNama = getIntent().getStringExtra(MyContants.)
        idDriver = getIntent().getStringExtra(MyContants.IDDRIVER);

        ratingReview.setOnRatingBarChangeListener(new RatingBar.OnRatingBarChangeListener() {
            @Override
            public void onRatingChanged(RatingBar ratingBar, float rating, boolean fromUser) {
                nilaiRating = rating;
            }
        });


    }


    @OnClick({R.id.cboReview, R.id.btnReview})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.cboReview:
                break;
            case R.id.btnReview:
                submitAction();
                break;
        }
    }

    private void submitAction() {
        String iduser = manager.getIdUser();
        String device = HeroHelper.getDeviceUUID(this);
        String token = manager.getToken();
        String comment = edtReviewComment.getText().toString();
        InitRetrofit.getInstance().review(
                token,device,iduser,idDriver,idBooking,String.valueOf(nilaiRating),comment
        ).enqueue(new Callback<ResponseDetailDriver>() {
            @Override
            public void onResponse(Call<ResponseDetailDriver> call, Response<ResponseDetailDriver> response) {
                if (response.isSuccessful()){
                    String result = response.body().getResult();
                    String msg = response.body().getMsg();
                    if (result.equals("true")){
                        Toast.makeText(ReviewActivity.this, msg, Toast.LENGTH_SHORT).show();
                        dataDriver = (DataDetailDriver) response.body().getData();
                        txtReviewUserNama.setText(dataDriver.getUserNama());
                        Toast.makeText(ReviewActivity.this, "Review Success, Thank You !", Toast.LENGTH_SHORT).show();
                        startActivity(new Intent(ReviewActivity.this,MainActivity.class));
                        finish();
                    }else{
                        Toast.makeText(ReviewActivity.this, msg, Toast.LENGTH_SHORT).show();
                    }
                }
            }

            @Override
            public void onFailure(Call<ResponseDetailDriver> call, Throwable t) {
                Toast.makeText(ReviewActivity.this, "Gagal"+t.getLocalizedMessage(), Toast.LENGTH_SHORT).show();
            }
        });

    }
}
