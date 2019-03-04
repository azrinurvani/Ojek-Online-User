package com.mobile.azrinurvani.ojekonline;

import android.content.DialogInterface;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.os.Build;
import android.os.Bundle;
import android.support.v4.app.ActivityCompat;
import android.support.v7.app.AlertDialog;
import android.support.v7.app.AppCompatActivity;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;

import com.mobile.azrinurvani.ojekonline.helper.SessionManager;
import com.mobile.azrinurvani.ojekonline.view.activity.GoRideActivity;
import com.mobile.azrinurvani.ojekonline.view.activity.HistoryActivity;
import com.mobile.azrinurvani.ojekonline.view.activity.LoginRegisterActivity;

public class MainActivity extends AppCompatActivity {

    public SessionManager manager;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_halaman_utama);
        manager = new SessionManager(this);
    }

    public void onGoride(View view) {
        startActivity(new Intent(MainActivity.this, GoRideActivity.class));
        //cek permission untuk os marshmellow ke atas
        if (ActivityCompat.checkSelfPermission(this, android.Manifest.permission.ACCESS_FINE_LOCATION) != PackageManager.PERMISSION_GRANTED && ActivityCompat.checkSelfPermission(this,
                android.Manifest.permission.ACCESS_COARSE_LOCATION) != PackageManager.PERMISSION_GRANTED) {
            if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.M
                    && checkSelfPermission(android.Manifest.permission.ACCESS_COARSE_LOCATION)
                    != PackageManager.PERMISSION_GRANTED
                    && checkSelfPermission(android.Manifest.permission.ACCESS_FINE_LOCATION)
                    != PackageManager.PERMISSION_GRANTED) {
                requestPermissions(
                        new String[]{android.Manifest.permission.ACCESS_COARSE_LOCATION,
                                android.Manifest.permission.ACCESS_FINE_LOCATION},
                        110);
            }
           return;

        }
    }

    public void onHistory(View view) {
        startActivity(new Intent(MainActivity.this, HistoryActivity.class));

    }

    //    method override dari AppCompactActivity untuk menampilkan menu
    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        getMenuInflater().inflate(R.menu.main_menu, menu);
        return super.onCreateOptionsMenu(menu);
    }

    //untuk memilih item yang ada di menu
    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        int id = item.getItemId();
        if (id == R.id.history) {
            startActivity(new Intent(this, HistoryActivity.class));
        } else if (id == R.id.profil) {

        } else {
            AlertDialog.Builder builder = new AlertDialog.Builder(this);
            builder.setTitle("Keluar ?");
            builder.setMessage("apakah anda yakin logout aplikasi ?");
            builder.setPositiveButton("Yes", new DialogInterface.OnClickListener() {
                @Override
                public void onClick(DialogInterface dialog, int which) {
                    manager.logout();
                    startActivity(new Intent(MainActivity.this, LoginRegisterActivity.class));
                    finish();

                }
            });
            builder.setNegativeButton("No", new DialogInterface.OnClickListener() {
                @Override
                public void onClick(DialogInterface dialog, int which) {

                }
            });
            builder.show();
        }
        return super.onOptionsItemSelected(item);
    }
}
