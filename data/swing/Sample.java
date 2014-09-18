import java.awt.*;
import java.awt.event.*;
import javax.swing.event.*;  
import java.awt.geom.*;
import java.awt.image.*;
import javax.swing.*;

/*
拡大縮小のできる簡単なお絵描きプログラム
表示領域の左上を基準にして拡大縮小する

問題となっている現象
２回拡大してから、スクロールバーで右下に移動
その後に２回続けて拡大すると正常に移動しないです

半分から右下（もっとも右、下の場合は除く）で拡大すると
正常に移動しないみたいです
*/

public class Sample extends JPanel implements ActionListener{
	int PWIDTH = 400 , PHEIGHT = 300;

	BufferedImage bi;
	Graphics2D bg;
	Canvas canvas;
	
	double zoom = 1.0;
	JButton zoomin , zoomout;
	JScrollPane spCanvas;

	Sample() {
		canvas = new Canvas( );
		bi = new BufferedImage(PWIDTH , PHEIGHT , BufferedImage.TYPE_INT_RGB);
		bg = (Graphics2D)bi.createGraphics( );
		bg.fillRect(0 , 0 , PWIDTH , PHEIGHT);
		bg.setColor(Color.black);
		canvas.setPreferredSize(new Dimension(PWIDTH,PHEIGHT));

		spCanvas = new JScrollPane(canvas);
		spCanvas.setPreferredSize(new Dimension(PWIDTH + 100 , PHEIGHT + 100));
		setLayout(new BorderLayout( ));
		add(spCanvas , BorderLayout.CENTER);

		JPanel cp = new JPanel();
		cp.add(new JLabel(""));

		zoomin = new JButton("Zoom in"); zoomin.addActionListener(this); cp.add(zoomin);
		zoomout = new JButton("Zoom out"); zoomout.addActionListener(this); cp.add(zoomout);

		JPanel cpp = new JPanel(new FlowLayout( ));
		cpp.add(cp);
		add(cpp , BorderLayout.NORTH);
	}

	public void zooming(double zoom) {
		double origzoom = this.zoom;
		this.zoom = zoom;
		Point p = spCanvas.getViewport( ).getViewPosition( ); //左上の座標
		canvas.setPreferredSize(new Dimension((int)( PWIDTH * zoom ) , (int)( PHEIGHT * zoom )));
		canvas.revalidate( );
		// 左上の位置を拡大縮小した大きさでの位置にあわせる
        //<ins>
        Point pt = new Point((int)( p.x * zoom / origzoom ) , (int)( p.y * zoom / origzoom ));
        canvas.scrollRectToVisible(new Rectangle(pt, spCanvas.getViewport().getSize()));
        //</ins>
        spCanvas.getViewport( ).setViewPosition(new Point((int)( p.x * zoom / origzoom ) , (int)( p.y * zoom / origzoom )));   //元と同じ点が左上の座標に来るように調整。
    }

	public void actionPerformed(ActionEvent e) {
		Object orig = e.getSource( );
		if( orig == zoomin ) { zooming(zoom * 2); repaint( ); return; }
		if( orig == zoomout ) { zooming(zoom / 2); repaint( ); return; }
	}

	class Canvas extends JPanel implements MouseListener,MouseMotionListener {
		int x0 , y0;
		boolean isClicked = false;

		Canvas() {
			addMouseListener(this);
			addMouseMotionListener(this);
			setBackground(Color.GRAY);
		}
		protected void paintComponent(Graphics g) {
			super.paintComponent(g);
			g.drawImage(bi , 0 , 0 , (int)( PWIDTH * zoom ) , (int)( PHEIGHT * zoom ) , this);
		}

		public void mousePressed(MouseEvent e) {
			if( javax.swing.SwingUtilities.isLeftMouseButton(e) ) {
				// 左クリック時の処理
				isClicked = true;
				x0 = (int)( e.getX( ) / zoom );
				y0 = (int)( e.getY( ) / zoom );
			}
		}
		public void mouseReleased(MouseEvent e) {
			if( javax.swing.SwingUtilities.isLeftMouseButton(e) ) {
				// 左クリック時の処理
				isClicked = false;
			}
		}

		public void mouseMoved(MouseEvent e) {

		}
		public void mouseDragged(MouseEvent e) {
			if( isClicked ) {
				int x1 , y1;
				x1 = (int)( e.getX( ) / zoom );
				y1 = (int)( e.getY( ) / zoom );
				bg.setColor(Color.BLACK);
				bg.drawLine(x0 , y0 , x1 , y1);
				//System.out.println("x,y" + x0 + "," + y0 + "x11y" + x1 + "," + y1);
				x0 = x1;
				y0 = y1;
				repaint( );
			}
		}
		public void mouseEntered(MouseEvent e) { }
		public void mouseExited(MouseEvent e) { }
		public void mouseClicked(MouseEvent e) { }

	}

	public static void main(String args[]) {
		JFrame frame = new JFrame( );
		frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
		Sample h = new Sample( );
		frame.getContentPane( ).add(h , BorderLayout.CENTER);
		frame.pack( );
		frame.setVisible(true);
	}
}
