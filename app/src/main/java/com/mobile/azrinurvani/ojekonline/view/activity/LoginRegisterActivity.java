package com.mobile.azrinurvani.ojekonline.view.activity;

import android.app.ProgressDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.os.Build;
import android.os.Bundle;
import android.support.v4.app.ActivityCompat;
import android.support.v7.app.AlertDialog;
import android.support.v7.app.AppCompatActivity;
import android.text.TextUtils;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.Button;
import android.widget.RelativeLayout;
import android.widget.TextView;
import android.widget.Toast;

import com.mobile.azrinurvani.ojekonline.MainActivity;
import com.mobile.azrinurvani.ojekonline.R;
import com.mobile.azrinurvani.ojekonline.helper.HeroHelper;
import com.mobile.azrinurvani.ojekonline.helper.SessionManager;
import com.mobile.azrinurvani.ojekonline.model.DataLoginRegis;
import com.mobile.azrinurvani.ojekonline.model.ResponseLoginRegister;
import com.mobile.azrinurvani.ojekonline.network.InitRetrofit;
import com.mobile.azrinurvani.ojekonline.presenter.LoginRegisterPresenter;
import com.mobile.azrinurvani.ojekonline.view.LoginRegisterView;
import com.rengwuxian.materialedittext.MaterialEditText;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.OnClick;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class LoginRegisterActivity extends AppCompatActivity implements LoginRegisterView{
//   TODO 2 buat LoginRegister tanpa layout dan set layout menjadi activity_loginregister
//    dan generate layout menggunakan ButterKnife
    @BindView(R.id.txt_rider_app)
    TextView txtRiderApp;
    @BindView(R.id.btnSignIn)
    Button btnSignIn;
    @BindView(R.id.btnRegister)
    Button btnRegister;
    @BindView(R.id.rootlayout)
    RelativeLayout rootlayout;

    public LoginRegisterPresenter loginRegisterPersenter;
    public ProgressDialog loading;
    public DataLoginRegis dataLogin;
    public SessionManager session;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_loginregister);
        permisionDevice();

        ButterKnife.bind(this);

        loading = new ProgressDialog(LoginRegisterActivity.this);
        loginRegisterPersenter = new LoginRegisterPresenter(this);


    }

    private void permisionDevice() {
        if (ActivityCompat.checkSelfPermission(this, android.Manifest.permission.READ_PHONE_STATE) != PackageManager.PERMISSION_GRANTED) {
            if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.M
                    && checkSelfPermission(android.Manifest.permission.READ_PHONE_STATE)
                    != PackageManager.PERMISSION_GRANTED
                    ) {
                requestPermissions(
                        new String[]{android.Manifest.permission.READ_PHONE_STATE},
                        1110);


            }
            return;
        }
    }

    @OnClick({R.id.btnSignIn, R.id.btnRegister})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.btnSignIn:
                login();
                break;
            case R.id.btnRegister:
                register();
                break;
        }
    }

    private void login() {
        AlertDialog.Builder builder = new AlertDialog.Builder(this);
        builder.setTitle("Login");
        builder.setMessage(R.string.messagelogin);
        builder.setCancelable(false);
        LayoutInflater inflater = getLayoutInflater();
        View formLogin = inflater.inflate(R.layout.layout_login, null);
        Toast.makeText(this, "test", Toast.LENGTH_SHORT).show();
        final ViewHolderLogin viewHolderLogin = new ViewHolderLogin(formLogin);
        builder.setView(formLogin);
        builder.setPositiveButton("Login", new DialogInterface.OnClickListener() {

            @Override
            public void onClick(DialogInterface dialogInterface, int i) {
                //Aksi apabila Login di klik terdapat di dialog.getButton()
            }
        });
        builder.setNegativeButton("Cancel", new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialogInterface, int i) {
                dialogInterface.dismiss();
            }
        });

        final AlertDialog dialog = builder.create();
        dialog.show();
        dialog.getButton(AlertDialog.BUTTON_POSITIVE).setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                if (TextUtils.isEmpty(viewHolderLogin.edtEmail.getText().toString().trim())) {
                    viewHolderLogin.edtEmail.setError(getString(R.string.requireemail));
                } else if (TextUtils.isEmpty(viewHolderLogin.edtPassword.getText().toString().trim())) {
                    viewHolderLogin.edtPassword.setError(getString(R.string.requirepassword));
                }else {
//                   loginRegisterPersenter.loginUser(device,email,password,dialogInterface);
                    proseslogin(viewHolderLogin,dialog);

                }
            }
        });
    }

    private void proseslogin(ViewHolderLogin viewHolderLogin, final DialogInterface dialogInterface) {
        final ProgressDialog loading = ProgressDialog.show(this, "Proses login", "loading");
        String device = HeroHelper.getDeviceUUID(this);
        InitRetrofit.getInstance().login(
                device,
                viewHolderLogin.edtEmail.getText().toString(),
                viewHolderLogin.edtPassword.getText().toString()
        ).enqueue(new Callback<ResponseLoginRegister>() {
            @Override
            public void onResponse(Call<ResponseLoginRegister> call, Response<ResponseLoginRegister> response) {
                if (response.isSuccessful()) {
                    String result = response.body().getResult();
                    String msg = response.body().getMsg();
                    if (result.equals("true")) {
                        Toast.makeText(LoginRegisterActivity.this, msg, Toast.LENGTH_SHORT).show();
                        dialogInterface.dismiss();
                        String token = response.body().getToken();
                        dataLogin = response.body().getData();
                        session = new SessionManager(LoginRegisterActivity.this);
                        session.createLoginSession(token);
                        session.setIduser(dataLogin.getIdUser());
                        startActivity(new Intent(LoginRegisterActivity.this, MainActivity.class));
                        finish();
                    } else {
                        Toast.makeText(LoginRegisterActivity.this, msg, Toast.LENGTH_SHORT).show();
                    }
                    loading.dismiss();
                }
            }

            @Override
            public void onFailure(Call<ResponseLoginRegister> call, Throwable t) {
                Toast.makeText(LoginRegisterActivity.this, "Gagal " + t.getLocalizedMessage(), Toast.LENGTH_SHORT).show();
                loading.dismiss();
            }
        });
    }

    private void register() {
        AlertDialog.Builder builder = new AlertDialog.Builder(this);
        builder.setTitle("Register");
        builder.setMessage(R.string.messageregister);
        builder.setCancelable(false);
        LayoutInflater inflater = getLayoutInflater();
        View formRegister = inflater.inflate(R.layout.layout_register, null);

        final ViewHolderRegister viewHolderRegister = new ViewHolderRegister(formRegister);
        builder.setView(formRegister);
        builder.setPositiveButton("Register", new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialogInterface, int i) {
                //cek validasi
                String email = viewHolderRegister.edtEmail.getText().toString().trim();
                String password = viewHolderRegister.edtPassword.getText().toString().trim();
                String phone = viewHolderRegister.edtPhone.getText().toString().trim();
                String name = viewHolderRegister.edtName.getText().toString().trim();

                if (TextUtils.isEmpty(email)) {
                    viewHolderRegister.edtEmail.setError(getString(R.string.requireemail));
                } else if (TextUtils.isEmpty(password)) {
                    viewHolderRegister.edtPassword.setError(getString(R.string.requirepassword));
                } else if (TextUtils.isEmpty(name)) {
                    viewHolderRegister.edtName.setError(getString(R.string.requirename));
                } else if (TextUtils.isEmpty(phone)) {
                    viewHolderRegister.edtPhone.setError(getString(R.string.requirephone));
                } else {
                    loginRegisterPersenter.registerDataUser(name,phone,email,password,dialogInterface);

                }
            }
        });

        builder.setNegativeButton("Cancel", new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialogInterface, int i) {
                dialogInterface.dismiss();
            }
        });
        builder.show();
    }

    @Override
    public void showLoading() {
        loading.setTitle("Sedang memproses");
        loading.setMessage("loading...");
        loading.show();
    }

    @Override
    public void hideLoading() {
        loading.dismiss();
    }

    @Override
    public void hideDialog(DialogInterface dialogInterface) {
        dialogInterface.dismiss();
    }

    @Override
    public void toastMsg(String message) {
        Toast.makeText(this, message, Toast.LENGTH_SHORT).show();
    }

    @Override
    public void errorMessage(String message) {
        Toast.makeText(this, message, Toast.LENGTH_SHORT).show();
    }

    @Override
    public void pindahKelas(Class classTujuan) {
        Intent intent = new Intent(this,classTujuan);
        startActivity(intent);
    }


    static class ViewHolderRegister {
        @BindView(R.id.edtEmail)
        MaterialEditText edtEmail;
        @BindView(R.id.edtPassword)
        MaterialEditText edtPassword;
        @BindView(R.id.edtName)
        MaterialEditText edtName;
        @BindView(R.id.edtPhone)
        MaterialEditText edtPhone;

        ViewHolderRegister(View view) {
            ButterKnife.bind(this, view);
        }
    }

    static class ViewHolderLogin {
        @BindView(R.id.edtEmail)
        MaterialEditText edtEmail;
        @BindView(R.id.edtPassword)
        MaterialEditText edtPassword;

        ViewHolderLogin(View view) {
            ButterKnife.bind(this, view);
        }
    }
}
