package com.mobile.azrinurvani.ojekonline.view.activity;

import android.content.DialogInterface;
import android.content.Intent;
import android.os.Bundle;
import android.support.v7.app.AlertDialog;
import android.support.v7.app.AppCompatActivity;
import android.widget.Button;
import android.widget.Toast;

import com.mobile.azrinurvani.ojekonline.R;
import com.mobile.azrinurvani.ojekonline.helper.HeroHelper;
import com.mobile.azrinurvani.ojekonline.helper.MyContants;
import com.mobile.azrinurvani.ojekonline.helper.SessionManager;
import com.mobile.azrinurvani.ojekonline.model.ResponseWaitingDriver;
import com.mobile.azrinurvani.ojekonline.network.InitRetrofit;

import java.util.Timer;
import java.util.TimerTask;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.OnClick;
import pl.bclogic.pulsator4droid.library.PulsatorLayout;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class WaitingDriverActivity extends AppCompatActivity {

    @BindView(R.id.pulsator)
    PulsatorLayout pulsator;
    @BindView(R.id.buttoncancel)
    Button buttoncancel;
    public int idbooking;
    public Timer timer;
    public SessionManager session;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_find_driver);
        ButterKnife.bind(this);

        pulsator.start();
        idbooking = getIntent().getIntExtra(MyContants.IDBOOKING,0);
        cekStatusBooking();
        timer = new Timer();

    }

    private void cekStatusBooking() {
        InitRetrofit.getInstance().cekStatusDriver(String.valueOf(idbooking)).enqueue(new Callback<ResponseWaitingDriver>() {
            @Override
            public void onResponse(Call<ResponseWaitingDriver> call, Response<ResponseWaitingDriver> response) {
                if (response.isSuccessful()){
                    String result = response.body().getResult();
                    String msg = response.body().getMsg();

                    if (result.equals("true")){
                        String iddriver = response.body().getDriver();
                        Intent i = new Intent(WaitingDriverActivity.this,DetailDriverActivity.class);
                        i.putExtra(MyContants.IDDRIVER,iddriver);
//                        i.putExtra(MyContants.NAMADRIVER,)
                        startActivity(i);
                        finish();
                        Toast.makeText(WaitingDriverActivity.this, msg, Toast.LENGTH_SHORT).show();
                    }else{
                        Toast.makeText(WaitingDriverActivity.this, msg, Toast.LENGTH_SHORT).show();
                    }
                }
            }

            @Override
            public void onFailure(Call<ResponseWaitingDriver> call, Throwable t) {
                Toast.makeText(WaitingDriverActivity.this, "Gagal "+t.getLocalizedMessage(), Toast.LENGTH_SHORT).show();
            }
        });
    }
    @OnClick(R.id.buttoncancel)
    public void onViewClicked() {
        session = new SessionManager(this);
        final String device = HeroHelper.getDeviceUUID(this);
        final String token = session.getToken();
        AlertDialog.Builder builder = new AlertDialog.Builder(this);
        builder.setTitle("Cancel ");
        builder.setMessage("Apakah anda yakin untuk cancel orderan ini ?");
        builder.setPositiveButton("Yes", new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialogInterface, int i) {
                InitRetrofit.getInstance().cancelBooking(String.valueOf(idbooking),device,token)
                .enqueue(new Callback<ResponseWaitingDriver>() {
                    @Override
                    public void onResponse(Call<ResponseWaitingDriver> call, Response<ResponseWaitingDriver> response) {
                        if (response.isSuccessful()){
                            String result = response.body().getResult();
                            String msg = response.body().getMsg();
                            if (result.equals("true")){
                                Toast.makeText(WaitingDriverActivity.this, msg, Toast.LENGTH_SHORT).show();
                                finish();
                            }else{
                                Toast.makeText(WaitingDriverActivity.this, msg, Toast.LENGTH_SHORT).show();
                            }
                        }
                    }

                    @Override
                    public void onFailure(Call<ResponseWaitingDriver> call, Throwable t) {
                        Toast.makeText(WaitingDriverActivity.this,"Gagal "+t.getLocalizedMessage(),Toast.LENGTH_SHORT).show();


                    }
                });
            }
        });
        builder.show();
    }

    @Override
    protected void onResume() {
        super.onResume();
        runOnUiThread(new Runnable() {
            @Override
            public void run() {
                timer.schedule(new TimerTask() {
                    @Override
                    public void run() {
                        cekStatusBooking();
                    }
                },0,3000);
            }
        });
    }

    @Override
    protected void onPause() {
        super.onPause();
        timer.cancel();
    }
}
